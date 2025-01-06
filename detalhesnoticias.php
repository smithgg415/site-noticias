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

    // Consulta os comentários da notícia
    $sqlComentarios = 'SELECT c.com_codigo, c.com_conteudo, c.com_criadoem, u.usu_nome, u.usu_codigo 
                       FROM comentarios c 
                       JOIN usuarios u ON c.usu_codigo = u.usu_codigo 
                       WHERE c.not_codigo = :not_codigo
                       ORDER BY c.com_criadoem DESC';
    $stmComentarios = $conexao->prepare($sqlComentarios);
    $stmComentarios->bindValue(':not_codigo', $noticiaId, PDO::PARAM_INT);
    $stmComentarios->execute();
    $comentarios = $stmComentarios->fetchAll(PDO::FETCH_OBJ) ?: [];
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia->not_titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styledetails.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: #4B6CC1;">
        <a class="navbar-brand text-white fw-bold" href="#">
            <i class="bi bi-newspaper"></i> INFONEWS
        </a>
        <a href="index.php" class="btn btn-light text-primary">Voltar</a>

    </nav>
    <div class="container mt-5">
        <div class="card mb-4">
            <img src="<?= htmlspecialchars($noticia->not_imagem) ?>" class="img-fluid" alt="<?= htmlspecialchars($noticia->not_titulo) ?>">
            <div class="card-header">
                <h1 class="noticia-title"><?= htmlspecialchars($noticia->not_titulo) ?></h1>
            </div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($noticia->not_conteudo)) ?></p>
            </div>
            <div class="card-footer">
                <small>Publicado em <?= date('d/m/Y H:i', strtotime($noticia->not_publicado_em)) ?></small>
            </div>
        </div>

        <div class="comentarios">
            <?php foreach ($comentarios as $comentario) : ?>
                <div class="card mb-3 position-relative">
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
        </div>

        <?php if (count($comentarios) == 0) : ?>
            <p class="text-muted">Ainda não há comentários nesta notícia.</p>
        <?php endif; ?>

        <?php if ($_SESSION['logado099']) : ?>
            <div class="comment-box">
                <h4>Adicionar Comentário</h4>
                <form method="POST" action="actioncomentario.php">
                    <input type="hidden" name="acao" value="incluir">
                    <input type="hidden" name="not_codigo" value="<?= $noticia->not_codigo ?>">
                    <textarea name="conteudo" rows="4" required placeholder="Digite seu comentário..." class="form-control"></textarea>
                    <button type="submit" class="mt-3">Enviar Comentário</button>
                </form>
            </div>
        <?php else : ?>
            <div class="message-container">
                <a href="login.php" class="login-link"><i class="bi bi-box-arrow-in-right icon"></i> <b>Faça login</b> para comentar.</a>
            </div>
        <?php endif; ?>
    </div>

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