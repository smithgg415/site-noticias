<?php
require 'bd/conexao.php';
$conexao = conexao::getInstance();
$login_err = ""; // Inicializando a variável de erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        // Prepara a consulta para pegar os dados do usuário
        $sql = "SELECT * FROM usuarios WHERE usu_email = :email";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $id = $row['usu_codigo'];
                $hashed_password = $row['usu_senha'];
                $nivel = $row['usu_nivel'];

                // Verificando se a senha inserida corresponde ao hash
                if (password_verify($senha, $hashed_password)) {
                    // Inicia a sessão e armazena os dados do usuário
                    session_start();
                    $_SESSION['logado099'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['nome'] = $row['usu_nome'];
                    $_SESSION['nivel'] = $nivel;

                    // Redireciona para a página principal
                    header("Location: index.php");
                    exit;
                } else {
                    $login_err = "Email ou senha incorretos."; // Erro ao verificar senha
                }
            } else {
                $login_err = "Email ou senha incorretos."; // Caso o email não seja encontrado
            }
        } else {
            $login_err = "Ops! Algo deu errado. Por favor, tente novamente mais tarde."; // Erro ao executar consulta
        }
    } else {
        $login_err = "Por favor, preencha todos os campos."; // Erro caso algum campo esteja vazio
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #fff, #2c006c, #2c006c, #5e2a8e, #9b4fd3, #5e2a8e, #2c006c, #2c006c, #fff);
            background-size: 500% 100%;
            animation: gradiente-animado 2.3s infinite alternate;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradiente-animado {
            0% {
                background-position-x: 0%;
            }

            100% {
                background-position-x: 100%;
            }
        }

        .meteor {
            position: absolute;
            top: -10px;
            background: white;
            border-radius: 50%;
            animation: meteor-fall 2s infinite linear;
        }
        @media (max-width: 576px) {
            .meteor {
                display: none;
            }
        }

        @keyframes meteor-fall {
            0% {
                transform: translateY(-100px) translateX(0);
            }

            100% {
                transform: translateY(100vh) translateX(50px);
            }
        }

        .meteor:nth-child(odd) {
            animation-duration: 1.5s;
            animation-delay: 0.5s;
        }

        .meteor:nth-child(even) {
            animation-duration: 2.5s;
            animation-delay: 1s;
        }

        .container {
            background: rgba(255, 255, 255);
            padding: 2rem;
            padding-right: 29px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
            position: relative;
            z-index: 1;
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

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-start-start-radius: 5px;
            border-end-start-radius: 5px;
            font-size: 1rem;
        }

        input[type="text"],
        input[type="email"] {
            width: 92.6%;
            padding: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .password-container {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .eye-icon {
            position: relative;
            right: 0;
            cursor: pointer;
            font-size: 1.2rem;
            margin-bottom: 24px;
            border: 1px solid #ccc;
            padding: 8.2px;
            border-top-right-radius: 10px;
            border-end-end-radius: 10px;
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
    <div class="meteor" style="left: 5%; width: 4px; height: 4px;"></div>
    <div class="meteor" style="left: 10%; width: 3px; height: 3px;"></div>
    <div class="meteor" style="left: 15%; width: 7px; height: 7px;"></div>
    <div class="meteor" style="left: 20%; width: 5px; height: 5px;"></div>
    <div class="meteor" style="left: 25%; width: 4px; height: 4px;"></div>
    <div class="meteor" style="left: 30%; width: 6px; height: 6px;"></div>
    <div class="meteor" style="left: 35%; width: 5px; height: 5px;"></div>
    <div class="meteor" style="left: 40%; width: 6px; height: 6px;"></div>
    <div class="meteor" style="left: 45%; width: 3px; height: 3px;"></div>
    <div class="meteor" style="left: 50%; width: 7px; height: 7px;"></div>
    <div class="meteor" style="left: 55%; width: 5px; height: 5px;"></div>
    <div class="meteor" style="left: 60%; width: 6px; height: 6px;"></div>
    <div class="meteor" style="left: 65%; width: 4px; height: 4px;"></div>
    <div class="meteor" style="left: 70%; width: 5px; height: 5px;"></div>
    <div class="meteor" style="left: 75%; width: 3px; height: 3px;"></div>
    <div class="meteor" style="left: 80%; width: 7px; height: 7px;"></div>
    <div class="meteor" style="left: 85%; width: 6px; height: 6px;"></div>
    <div class="meteor" style="left: 90%; width: 5px; height: 5px;"></div>
    <div class="meteor" style="left: 95%; width: 4px; height: 4px;"></div>

    <div class="container mt-5">
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
            <div class="password-container">
                <input type="password" id="senha" name="senha" required>
                <span class="eye-icon" onclick="togglePassword()">
                    <i id="eye-icon" class="bi bi-eye"></i>
                </span>
            </div>
            <button type="submit">Entrar</button>
            <p>Não tem uma conta? <a href="register.php">Registre-se</a></p>
        </form>
    </div>

    <script>
        function togglePassword() {
            var senhaField = document.getElementById('senha');
            var eyeIcon = document.getElementById('eye-icon');
            if (senhaField.type === 'password') {
                senhaField.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                senhaField.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
