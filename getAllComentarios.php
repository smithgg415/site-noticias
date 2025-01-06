<?php
require 'bd/conexao.php';
session_start();

$notCodigo = filter_input(INPUT_GET, 'not_codigo', FILTER_VALIDATE_INT);
if (!$notCodigo) {
    header('Location: index.php');
    exit;
}

$conexao = conexao::getInstance();
$sql = 'SELECT c.com_codigo, c.com_conteudo, c.com_criadoem, u.usu_nome, u.usu_codigo 
        FROM comentarios c 
        JOIN usuarios u ON c.usu_codigo = u.usu_codigo 
        WHERE c.not_codigo = :not_codigo';
$stm = $conexao->prepare($sql);
$stm->bindValue(':not_codigo', $notCodigo, PDO::PARAM_INT);
$stm->execute();
$comentarios = $stm->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Header responsivo */
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.8rem;
            margin: 0;
        }

        .header p {
            font-size: 1rem;
            margin: 5px 0 0;
        }

        .comment-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }

        .comment-meta {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 10px;
        }

        .options-btn {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .options-btn:hover {
            color: #007bff;
        }

        .options-menu {
            display: none;
            position: absolute;
            top: 40px;
            right: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 10px;
        }

        .options-menu a, 
        .options-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            background: none;
            border: none;
        }

        .options-menu a:hover,
        .options-menu button:hover {
            background-color: #f8f9fa;
            border-radius: 5px;
            color: #0056b3;
        }

        /* Botão de voltar */
        .back-btn {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .back-btn a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Comentários</h1>
        <p>Veja, edite e exclua comentários de maneira simples.</p>
    </div>

    <div class="container mt-4">
        <?php if (!empty($comentarios)): ?>
            <?php foreach ($comentarios as $comentario): ?>
                <div class="comment-card">
                    <p><?= htmlspecialchars($comentario->com_conteudo) ?></p>
                    <p class="comment-meta">
                    <strong>Por:</strong> <?= htmlspecialchars($comentario->usu_nome) ?>

                        | <strong>Data:</strong> <?= htmlspecialchars($comentario->com_criadoem) ?>
                    </p>

                    <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $comentario->usu_codigo): ?>
                        <!-- Botão de opções -->
                        <span class="options-btn" onclick="toggleOptionsMenu(<?= $comentario->com_codigo ?>)">&#x22EE;</span>

                        <!-- Menu suspenso de opções -->
                        <div id="options-menu-<?= $comentario->com_codigo ?>" class="options-menu">
                            <a href="editarcomentario.php?com_codigo=<?= $comentario->com_codigo ?>">Editar</a>
                            <form method="POST" action="actioncomentario.php" style="margin: 0;">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este comentário?')" class="text-danger">Excluir</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Nenhum comentário encontrado para esta notícia.
            </div>
        <?php endif; ?>

        <!-- Botão de voltar -->
        <div class="back-btn">
            <a href="index.php" class="btn btn-outline-primary">Voltar ao Início</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleOptionsMenu(commentId) {
            var menu = document.getElementById('options-menu-' + commentId);
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        // Fecha os menus ao clicar fora
        window.addEventListener('click', function(event) {
            if (!event.target.closest('.options-btn') && !event.target.closest('.options-menu')) {
                var menus = document.querySelectorAll('.options-menu');
                menus.forEach(function(menu) {
                    menu.style.display = 'none';
                });
            }
        });
    </script>
</body>

</html>
