<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado099'])) {
    $_SESSION['logado099'] = false;
    $_SESSION['id'] = 0;
    $_SESSION['nome'] = "Visitante";
    $_SESSION['nivel'] = "usuario";
}

// Conexão com o banco de dados
require "bd/conexao.php";
$conexao = conexao::getInstance();

// Recupera o ID da notícia
if (isset($_GET['id'])) {
    $noticiaId = $_GET['id'];

    // Consulta a notícia pelo ID
    $sqlNoticia = 'SELECT * FROM noticias WHERE not_codigo = :not_codigo';
    $stmNoticia = $conexao->prepare($sqlNoticia);
    $stmNoticia->bindValue(':not_codigo', $noticiaId, PDO::PARAM_INT);
    $stmNoticia->execute();
    $noticia = $stmNoticia->fetch(PDO::FETCH_OBJ);

    if (!$noticia) {
        echo "<p>Notícia não encontrada.</p>";
        exit;
    }

    $sqlComentarios = 'SELECT c.com_codigo, c.com_conteudo, c.com_criadoem, u.usu_nome, u.usu_codigo 
                    FROM comentarios c 
                    JOIN usuarios u ON c.usu_codigo = u.usu_codigo 
                    WHERE c.not_codigo = :not_codigo
                    ORDER BY c.com_criadoem DESC LIMIT 4';
    $stmComentarios = $conexao->prepare($sqlComentarios);
    $stmComentarios->bindValue(':not_codigo', $noticiaId, PDO::PARAM_INT);
    $stmComentarios->execute();
    $comentarios = $stmComentarios->fetchAll(PDO::FETCH_OBJ) ?: [];
} else {
    header("Location: index.php");
    exit;
}
$sql = "SELECT * FROM anuncios ORDER BY anu_codigo DESC LIMIT 1";
$stm = $conexao->prepare($sql);
$stm->execute();
$anuncios = $stm->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia->not_titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .noticia-img {
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            object-fit: cover;
        }

        .options-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            z-index: 1000;
            width: 150px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .options-menu button {
            width: 100%;
            padding: 10px;
            border: none;
            text-align: left;
            background: white;
            cursor: pointer;
        }

        .options-menu button:hover {
            background-color: #f8f9fa;
        }

        .comment-box textarea {
            resize: none;
            border-radius: 5px;
        }

        .message-container {
            background: rgba(255, 255, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
        }

        .noticia-img {
            height: 400px;
            object-fit: cover;
        }

        .comment-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .comment-box h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4B6CC1;
            margin-bottom: 15px;
        }

        .comment-box textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: none;
            font-size: 1rem;
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        .comment-box textarea:focus {
            border-color: #4B6CC1;
            outline: none;
            box-shadow: 0 0 5px rgba(75, 108, 193, 0.5);
        }

        .comment-box button {
            background-color: #4B6CC1;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .comment-box button:hover {
            background-color: #354d91;
            transform: scale(1.05);
        }

        .comment-box button:active {
            background-color: #2b3e7d;
            transform: scale(1);
        }

        .options-btn {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            float: right;
            position: relative;
            margin-left: 10px;
        }

        .options-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            z-index: 1000;
            width: 150px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .options-menu button {
            width: 100%;
            padding: 10px;
            border: none;
            text-align: left;
            background: white;
            cursor: pointer;
            font-size: 0.9rem;
            color: #495057;
        }

        .options-menu button:hover {
            background-color: #f8f9fa;
            color: #212529;
        }

        .options-menu form {
            margin: 0;
        }

        .share-buttons {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            justify-content: start;
        }

        .share-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: #fff;
            border-radius: 50%;
            padding: 12px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Cor de fundo para os ícones */
        .share-button.facebook {
            background-color: #3b5998;
        }

        .share-button.twitter {
            background-color: #1da1f2;
        }

        .share-button.whatsapp {
            background-color: #25d366;
        }

        /* Efeito de hover */
        .share-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .share-button i {
            font-size: 24px;
            color: white;
        }

        .text-muted {
            color: #6c757d;

        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            p {
                font-size: 1.7rem;
            }

            .text-muted {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 0.8rem;
            }

            p {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-4">
                <img src="<?= htmlspecialchars($noticia->not_imagem) ?>" alt="<?= htmlspecialchars($noticia->not_titulo) ?>" class="img-fluid noticia-img">
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm border-light mb-4">
                    <div class="card-body">
                        <h1 class="display-4 display-sm-5 display-xs-6 font-weight-bold text-dark"><?= htmlspecialchars($noticia->not_titulo) ?></h1>
                        <p class="lead text-muted"><?= nl2br(htmlspecialchars($noticia->not_conteudo)) ?></p>
                        <div class="text-right text-muted">
                            <small>Publicado em <?= date('d/m/Y H:i', strtotime($noticia->not_publicado_em)) ?></small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <h3 style="
                    text-align: left;margin-top:10px;
                    font-family:Verdana, Geneva, Tahoma, sans-serif
                    ">Compartilhe essa notícia!</h3>
                <div class="share-buttons mb-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://infonews/detalhesnoticias?id=' . $noticia->not_codigo) ?>" target="_blank" class="share-button facebook" title="Compartilhar no Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode($noticia->not_titulo . ' ' . 'https://infonews/detalhesnoticias?id=' . $noticia->not_codigo) ?>" target="_blank" class="share-button whatsapp" title="Compartilhar no WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
                <?php if ($_SESSION['logado099']) : ?>
                    <div class="comment-box">
                        <h4>Adicionar Comentário</h4>
                        <form method="POST" action="actioncomentario.php">
                            <input type="hidden" name="acao" value="incluir">
                            <input type="hidden" name="not_codigo" value="<?= $noticia->not_codigo ?>">
                            <textarea name="conteudo" required placeholder="Digite seu comentário..."></textarea>
                            <button type="submit" class="btn btn-primary mt-3">Comentar</button>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info">
                        <a href="login.php" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Faça login para comentar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6 comments-container">
                <h4 class="m-2">Comentários</h4>
                <?php foreach ($comentarios as $comentario) : ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <p><strong><?= htmlspecialchars($comentario->usu_nome) ?></strong>: <?= nl2br(htmlspecialchars($comentario->com_conteudo)) ?></p>
                            <small class="text-muted">Publicado em <?= date('d/m/Y H:i', strtotime($comentario->com_criadoem)) ?></small>
                            <?php if ($_SESSION['logado099'] && $_SESSION['id'] == $comentario->usu_codigo) : ?>
                                <span class="options-btn" onclick="toggleOptionsMenu(<?= $comentario->com_codigo ?>)">&#x22EE;</span>
                                <div id="options-menu-<?= $comentario->com_codigo ?>" class="options-menu">
                                    <form method="POST" action="actioncomentario.php" style="margin: 0;">
                                        <input type="hidden" name="acao" value="editar">
                                        <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                                        <button type="button" onclick="editComment(<?= $comentario->com_codigo ?>)">Editar</button>
                                        <input type="hidden" name="not_codigo" value="<?= $noticia->not_codigo ?>">
                                    </form>
                                    <form method="POST" action="actioncomentario.php" style="margin: 0;">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">Excluir</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($comentarios) > 0) : ?>
                    <hr>
                    <a class="text-muted" href="comentarios.php?id=<?= $noticia->not_codigo ?>">Ver todos os comentários</a>
                    <div class="card bg-light shadow-sm text-center mt-5">
                        <div class="card-body">
                            <hr>
                            <?php foreach ($anuncios as $anuncio) : ?>
                                <div class="card mb-3">
                                    <img src="<?= $anuncio->anu_imagem ?>" class="card-img-top" alt="<?= $anuncio->anu_titulo ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (count($comentarios) == 0) : ?>
                    <p class="text-muted">Ainda não há comentários nesta notícia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script>
        function toggleOptionsMenu(id) {
            const menu = document.getElementById(`options-menu-${id}`);
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }


        function editComment(id) {
            window.location.href = 'editarcomentario.php?com_codigo=' + id;
        }
    </script>
</body>

</html>