<?php
session_start();

if (!isset($_SESSION['logado099']) || $_SESSION['logado099'] !== true) {
    header('Location: index.php');
    exit;
}

require 'bd/conexao.php';

$com_codigo = filter_input(INPUT_GET, 'com_codigo', FILTER_VALIDATE_INT);

if (!$com_codigo) {
    echo "<div class='alert alert-danger text-center mt-5'>Comentário inválido ou não encontrado!</div>";
    exit;
}

$conexao = conexao::getInstance();
$sql = 'SELECT * FROM comentarios WHERE com_codigo = :com_codigo';
$stm = $conexao->prepare($sql);
$stm->bindValue(':com_codigo', $com_codigo, PDO::PARAM_INT);
$stm->execute();
$comentario = $stm->fetch(PDO::FETCH_OBJ);


if (!$comentario) {
    echo "<div class='alert alert-danger text-center mt-5'>Comentário não encontrado!</div>";
    exit;
}

if ((int)$comentario->usu_codigo !== (int)$_SESSION['id']) {
    echo "<div class='alert alert-danger text-center mt-5'>Você não tem permissão para editar este comentário.</div>";
    exit;
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comentário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #495057;
        }
        .container {
            max-width: 500px;
            margin-top: 100px;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #4B6CC1;
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
            padding: 20px 0;
        }
        .card-body {
            background-color: white;
            padding: 30px;
            border-top: 4px solid #4B6CC1;
        }
        .form-label {
            font-weight: 600;
            color: #4a4a4a;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            background-color: #f1f3f5;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #4B6CC1;
            box-shadow: 0 0 10px rgba(75, 108, 193, 0.3);
        }
        .btn-primary {
            background-color: #4B6CC1;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #3a5ba1;
        }
        .btn-secondary {
            background-color: #e1e8f0;
            border: none;
            color: #333;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #d3dae6;
        }
        .text-center {
            margin-top: 15px;
        }
        .text-muted {
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Editar Comentário
            </div>
            <div class="card-body">
                <form action="actioncomentario.php" method="post">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">

                    <div class="form-group">
                        <label for="conteudo" class="form-label">Conteúdo do Comentário</label>
                        <textarea 
                            class="form-control" 
                            id="conteudo" 
                            name="conteudo" 
                            rows="6" 
                            placeholder="Escreva seu comentário..." 
                            required
                            autofocus><?= htmlspecialchars($comentario->com_conteudo) ?></textarea>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>

                <div class="text-center text-muted mt-3">
                    <a href="index.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
