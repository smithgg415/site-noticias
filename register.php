<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
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
            border-radius: 5px;
            border-end-end-radius: 0;
            border-top-right-radius: 0;
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
        @media (max-width: 576px) {
            .meteor {
                display: none;
            }
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
                    <label for="senha" class="form-label">Senha:</label>
                    <div class="password-container">
                        <input type="password" id="senha" name="senha" required>
                        <span class="eye-icon" onclick="togglePassword()">
                            <i id="eye-icon" class="bi bi-eye"></i>
                        </span>
                    </div>
                    <input type="hidden" name="nivel" value="usuário">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>