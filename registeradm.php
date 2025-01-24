<?php
session_start();
if(!$_SESSION['logado099'] && $_SESSION['nivel'] != 'admin'){
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuário</title>
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .register-card {
            max-width: 500px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .register-card h1 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .btn-register {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
        }

        .btn-register:hover {
            background-color: #0056b3;
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h1>Registrar Administrador</h1>
        <form action="actionusuario.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
            </div>
            <input type="hidden" name="acao" value="incluir">
            <input type="hidden" name="nivel" id="nivel" value="admin">

            <button type="submit" class="btn btn-register">Registrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
