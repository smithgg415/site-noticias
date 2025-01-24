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
$not_codigo = filter_input(INPUT_GET, 'not_codigo', FILTER_VALIDATE_INT);
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM comentarios c WHERE c.com_codigo = :com_codigo';
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
$sql = "SELECT * FROM anuncios";
$stm = $conexao->prepare($sql);
$stm->execute();
$anuncios = $stm->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comentário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            color: white;
            padding: 16px 20px;
            font-size: 1.25rem;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-body {
            background-color: #ffffff;
            padding: 30px;
            border-top: 1px solid #e0e0e0;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ccc;
            padding: 12px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
        }

        .btn {
            background-color: #007bff;
            width: 100px;
            color: #fff;
            border: none;
            padding: 0.7rem;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-voltar {
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-align: center;
            align-items: center;
        }

        .btn-voltar:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }

        .btn:hover {
            transform: scale(1.05);
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #btn-save {
            background-color: #28a745;
            transition: all 0.3s ease;
        }

        #btn-delete {
            background-color: #dc3545;
            transition: all 0.3s ease;
        }

        .mt-4-custom {
            margin-top: 4rem !important;
        }

        .d-flex {
            gap: 15px;
        }

        .d-flex .btn {
            flex: 1;
            margin-right: 0;
        }

        .alert-custom {
            margin-top: 20px;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
            font-size: 1rem;
            border-left: 4px solid #dc3545;
        }

        .card .card-body h6 {
            font-size: 1.125rem;
            margin-bottom: 20px;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #343a40;
            cursor: pointer;
            position: relative;
            left: 360px;
            margin-bottom: 10px;
        }

        .btn-close:hover {
            color: #dc3545;
        }

        .card {
            position: relative;
        }

        @media (max-width: 768px) {
            .btn-close {
                left: 390px;
            }
        }
    </style>
</head>

<body>
<?php include 'header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h5 class="text-white fw-bold">Editar Comentário</h5>
                    </div>
                    <div class="card-body">
                        <form action="actioncomentario.php" method="post">
                            <input type="hidden" name="acao" value="editar">
                            <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                            <div class="mb-4">
                                <label for="conteudo" class="form-label text-dark fw-bold  ">Conteúdo do Comentário</label>
                                <textarea
                                    class="form-control"
                                    id="conteudo"
                                    name="conteudo"
                                    rows="6"
                                    placeholder="Escreva seu comentário..."
                                    required><?= htmlspecialchars($comentario->com_conteudo) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="detalhesnoticias.php?id=<?= $comentario->not_codigo ?>" class="btn-voltar btn-secondary col-5">
                                    <i class="bi-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" id="btn-save" class="btn  col-5 text-light">
                                    <i class="bi-floppy"></i> Salvar Alterações
                                </button>
                            </div>
                        </form>

                        <form action="actioncomentario.php" method="post" class="mt-4">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $comentario->com_codigo ?>">
                            <input type="hidden" name="not_codigo" value="<?= $comentario->not_codigo ?>">
                            <button type="submit" id="btn-delete" class="btn w-100 text-light" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">
                                <i class="bi-trash-fill"></i> Excluir Comentário
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mt-4 mt-lg-0" id="adSection">
                <div class="card bg-light shadow-sm text-center">
                    <button onclick="fecharAnuncio()" class="btn-close">
                        <i class="bi-x-lg"></i>
                    </button>
                    <div class="card-body">
                        <p class="fw-bold">Nossos parceiros</p>
                        <hr>
                        <?php foreach ($anuncios as $anuncio) : ?>
                            <div class="card mb-3">
                                <img src="<?= $anuncio->anu_imagem ?>" class="card-img-top" alt="<?= $anuncio->anu_titulo ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function fecharAnuncio() {
            document.getElementById('adSection').style.display = 'none';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>