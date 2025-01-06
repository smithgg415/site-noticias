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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h1>Editar Usuário</h1>
                    </div>
                    <div class="card-body">
                        <form action="actionusuario.php" method="post">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['usu_codigo']) ?>">
                            
                            <div class="form-group mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['usu_nome']) ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['usu_email']) ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" value="<?= htmlspecialchars($usuario['usu_senha']) ?>" required>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="nivel" class="form-label">Nível</label>
                                <select class="form-select" id="nivel" name="nivel" required>
                                    <option value="admin" <?= $usuario['usu_nivel'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="usuario" <?= $usuario['usu_nivel'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                </select>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success btn-lg">Salvar</button>
                                <a href="indexnoticia.php" class="btn btn-danger btn-lg">Cancelar</a>
                            </div>
                            <input type="hidden" name="acao" value="editar">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
