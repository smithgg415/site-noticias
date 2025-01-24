<?php
session_start();
require 'bd/conexao.php';

$conexao = conexao::getInstance();
$sql = "
    SELECT c.*, u.usu_nome, u.usu_foto
    FROM comentarios c
    JOIN usuarios u ON c.usu_codigo = u.usu_codigo
    WHERE c.not_codigo = :not_codigo ORDER BY c.com_criadoem DESC
";

$stm = $conexao->prepare($sql);
$stm->bindValue(':not_codigo', $_GET['id']);
$stm->execute();
$comentarios = $stm->fetchAll(PDO::FETCH_OBJ);
$sql = "SELECT * FROM noticias WHERE not_codigo = :not_codigo";
$stm = $conexao->prepare($sql);
$stm->bindValue(':not_codigo', $_GET['id']);
$stm->execute();
$noticia = $stm->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários de <?= htmlspecialchars($noticia->not_titulo) ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .comments-section {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .comment-card {
            position: relative;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-header strong {
            font-size: 1rem;
        }

        .comment-header .options-btn {
            cursor: pointer;
            font-size: 1.5rem;
            color: #666;
            background: none;
            border: none;
        }

        .comment-header .options-btn:hover {
            color: #333;
        }

        .options-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            z-index: 100;
            min-width: 150px;
        }

        .options-menu button {
            background: none;
            border: none;
            color: #007bff;
            padding: 8px 12px;
            text-align: left;
            width: 100%;
            font-size: 14px;
        }

        .options-menu button:hover {
            background-color: #f0f0f0;
        }

        .no-comments {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }

        .comments-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            letter-spacing: 0.5px;
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

        .link_user {
            text-decoration: none;
            color: #000;
        }

        .link_user:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="comments-section">
        <h2 class="comments-title">Comentários de "<?= $noticia->not_titulo ?>"</h2>

        <?php foreach ($comentarios as $comentario) : ?>
            <div class="comment-card">
                <div class="comment-header">
                    <?php if ($comentario->usu_codigo == $_SESSION['id']) : ?>
                        <a href="perfil.php" class="link_user">
                            <?php if (empty($comentario->usu_foto)) : ?>
                                <img src="img/perfil-padrao.png" alt="<?= htmlspecialchars($comentario->usu_nome) ?>" class="rounded-circle" width="30">
                            <?php else : ?>
                                <img src="<?= $comentario->usu_foto ?>" alt="<?= htmlspecialchars($comentario->usu_nome) ?>" class="rounded-circle" width="30">
                            <?php endif; ?>
                            <strong><?= htmlspecialchars($comentario->usu_nome) ?></strong>
                        </a>
                    <?php else : ?>
                        <a href="userAccount.php?id=<?= $comentario->usu_codigo ?>" class="link_user">
                            <?php if (empty($comentario->usu_foto)) : ?>
                                <img src="img/perfil-padrao.png" alt="<?= htmlspecialchars($comentario->usu_nome) ?>" class="rounded-circle" width="30">
                            <?php else : ?>
                                <img src="<?= $comentario->usu_foto ?>" alt="<?= htmlspecialchars($comentario->usu_nome) ?>" class="rounded-circle" width="30">
                            <?php endif; ?>
                            <strong><?= htmlspecialchars($comentario->usu_nome) ?></strong>
                        </a>
                    <?php endif; ?>
                    <?php if ($_SESSION['logado099'] && $_SESSION['id'] == $comentario->usu_codigo) : ?>
                        <button class="options-btn" onclick="toggleOptionsMenu(<?= $comentario->com_codigo ?>)">&#x22EE;</button>
                        <div id="options-menu-<?= $comentario->com_codigo ?>" class="options-menu">
                            <form method="POST" action="actioncomentario.php" style="margin: 0;">
                                <input type="hidden" name="acao" value="editar">
                                <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                                <button type="button" onclick="editComment(<?= $comentario->com_codigo ?>)">Editar</button>
                            </form>
                            <form method="POST" action="actioncomentario.php" style="margin: 0;">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">Excluir</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <p><?= nl2br(htmlspecialchars($comentario->com_conteudo)) ?></p>
                <?php if ($comentario->com_updated_at != $comentario->com_criadoem): ?>
                    <small class="text-muted"> Atualizado em <?= date('d/m/Y', strtotime($comentario->com_updated_at)) . " às " . date('H:i', strtotime($comentario->com_updated_at)) ?></small>
                <?php else: ?>
                    <small class="text-muted"> Publicado em <?= date('d/m/Y', strtotime($comentario->com_criadoem)) . " às " . date('H:i', strtotime($comentario->com_criadoem)) ?>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (count($comentarios) == 0) : ?>
            <p class="no-comments">Ainda não há comentários nesta notícia.</p>
        <?php endif; ?>
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
    <div class="footer">
        <?php include 'footer.php'; ?>
    </div>
    <script>
        function toggleOptionsMenu(id) {
            const menu = document.getElementById(`options-menu-${id}`);
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                document.querySelectorAll('.options-menu').forEach(m => m.style.display = 'none');
                menu.style.display = 'block';
            }
        }

        function editComment(id) {
            window.location.href = 'editarcomentario.php?com_codigo=' + id;
        }

        document.addEventListener('click', function(event) {
            const isOptionsButton = event.target.classList.contains('options-btn');
            if (!isOptionsButton) {
                document.querySelectorAll('.options-menu').forEach(menu => menu.style.display = 'none');
            }
        });
    </script>
</body>

</html>