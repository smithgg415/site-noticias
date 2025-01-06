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
    <link rel="stylesheet" href="css/stylelogin.css">
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