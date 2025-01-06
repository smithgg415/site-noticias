<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
    <link rel="stylesheet" href="css/stylelogin.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registrar Usuário</h2>
                <form action="actionusuario.php" method="post">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" required>
                    </div>
                    <input type="hidden" name="nivel" value="usuario">
                    <input type="hidden" name="acao" value="incluir">
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
                <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça login</a></p>
                <?php
                if (isset($_GET['mensagem'])) {
                    $mensagem = htmlspecialchars($_GET['mensagem']);
                    echo "
                    <div class='alert alert-info mt-3'>
                        $mensagem
                    </div>
                    ";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
