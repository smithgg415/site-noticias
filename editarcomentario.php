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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
        }

        .card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #4c6ef5;
            color: #fff;
            padding: 20px;
            font-size: 1.25rem;
            text-align: center;
        }

        .card-body {
            background-color: #fff;
            padding: 30px;
            border-top: 3px solid #4c6ef5;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #ddd;
            box-shadow: none;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: #4c6ef5;
            box-shadow: 0 0 5px rgba(76, 110, 245, 0.4);
        }

        .btn {
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #4c6ef5;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3b5df2;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .mt-4-custom {
            margin-top: 4rem !important;
        }

        .d-flex .btn {
            flex: 1;
            margin-right: 10px;
        }

        .alert-custom {
            margin-top: 20px;
            border-radius: 12px;
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h5>Editar Comentário</h5>
                    </div>
                    <div class="card-body">
                        <form action="actioncomentario.php" method="post">
                            <input type="hidden" name="acao" value="editar">
                            <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                            <div class="mb-4">
                                <label for="conteudo" class="form-label">Conteúdo do Comentário</label>
                                <textarea
                                    class="form-control"
                                    id="conteudo"
                                    name="conteudo"
                                    rows="6"
                                    placeholder="Escreva seu comentário..."
                                    required><?= htmlspecialchars($comentario->com_conteudo) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="detalhesnoticias.php" class="btn btn-secondary col-5">
                                    <i class="bi-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-primary col-5">
                                    <i class="bi-floppy"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>

                        <form action="actioncomentario.php" method="post" class="mt-4">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
                                <i class="bi-trash-fill"></i> Excluir Comentário
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                <div class="card bg-light shadow-sm text-center">
                    <div class="card-body">
                        <h6>Anúncio</h6>
                        <p>Conteúdo ou espaço para anúncios.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>