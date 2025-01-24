<?php
session_start();
if (!$_SESSION["logado099"]) {
    header("Location: index.php");
    exit;
}
include "bd/conexao.php";
$conexao = conexao::getInstance();
$sql = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
$stm = $conexao->prepare($sql);
$stm->bindValue(':id', $_GET['id']);
$stm->execute();
$usuario = $stm->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
    echo "<p>Usuário não encontrado.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background-color: transparent;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
        }

        .close-button:hover {
            color: #dc3545;
        }

        .ad-section {
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white text-center">
                        <h1 class="h3">Editar Usuário</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <form action="actionusuario.php" method="post">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['usu_codigo']) ?>">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">
                                            <i class="fas fa-user me-2"></i>Nome
                                        </label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['usu_nome']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>E-mail
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['usu_email']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="senha" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Senha
                                        </label>
                                        <input type="password" class="form-control" id="senha" name="senha" value="<?= htmlspecialchars($usuario['usu_senha']) ?>" required>
                                        <small class="text-muted">Sua senha está criptografada!</small>
                                    </div>
                                    <?php if ($_SESSION['nivel'] == 'admin') { ?>
                                        <div class="mb-3">
                                            <label for="nivel" class="form-label">
                                                <i class="fas fa-users-cog me-2"></i>Nível
                                            </label>
                                            <select class="form-select" id="nivel" name="nivel" required>
                                                <option value="admin" <?= $usuario['usu_nivel'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                                <option value="usuario" <?= $usuario['usu_nivel'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                            </select>
                                        </div>
                                    <?php } else {
                                        echo '<input type="hidden" name="nivel" value="usuario">';
                                    } ?>
                                    <div class="d-flex justify-content-between">
                                        <input type="hidden" name="acao" value="editar">
                                        <button type="submit" class="btn card-header text-light">
                                            <i class="fas fa-save me-2"></i>Salvar
                                        </button>
                                        <a href="indexnoticia.php" class="btn btn-danger">
                                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center mt-3">
                                <div class="ad-section border rounded p-4 w-100 text-center bg-light" id="adSection">
                                    <button class="close-button" onclick="fecharAnuncio()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <h2 class="h5" style="font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;letter-spacing: 2px;
                                    background:linear-gradient(to right, #000, #6c757d , #6c757d, #000);
                                    background-clip: text;
                                    color:transparent;
                                    
                                    ">Desenvolvedora de Software</h2>
                                    <img src="img/giacomellidevs.png" alt="Pré-visualização da Imagem" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function fecharAnuncio() {
            var adSection = document.getElementById("adSection");
            adSection.style.display = "none";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>