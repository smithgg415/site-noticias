<?php

require 'bd/conexao.php';
$conexao = conexao::getInstance();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        $sql = "SELECT * FROM usuarios WHERE usu_email = :email";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $id = $row['usu_codigo'];
                $hashed_password = $row['usu_senha'];
                $nivel = $row['usu_nivel'];

                if (password_verify($senha, $hashed_password)) {
                    session_start();
                    $_SESSION['logado099'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['nome'] = $row['usu_nome'];
                    $_SESSION['nivel'] = $nivel;

                    header("Location: index.php");
                    exit;
                } else {
                    $login_err = "Email ou senha incorretos.";
                }
            } else {
                $login_err = "Email ou senha incorretos.";
            }
        } else {
            $login_err = "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
    } else {
        $login_err = "Por favor, preencha todos os campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(45deg, #007BFF, #00BFFF, #1E90FF, #87CEFA, #B0E0E6);
            background-size: 500% 100%;
            animation: gradiente-animado 2.3s infinite alternate;
        }

        @keyframes gradiente-animado {
            0% {
                background-position-x: 0%;
            }

            100% {
                background-position-x: 100%;
            }
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            padding-right: 29px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 92%;
            padding: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php
        if (!empty($login_err)) {
            echo '<div class="error">' . $login_err . '</div>';
        }
        ?>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
            <p>Não tem uma conta? <a href="register.php">Registre-se</a></p>
        </form>
    </div>
</body>

</html>