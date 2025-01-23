<?php
session_start();

if (!isset($_SESSION['logado099']) || $_SESSION['logado099'] === false) {
    header("Location: login.php");
    exit();
}

require "bd/conexao.php";
$conexao = conexao::getInstance();

$sqlUsuario = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
$stmt = $conexao->prepare($sqlUsuario);
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_OBJ);

$sqlAtividades = 'SELECT c.com_conteudo, n.not_titulo, n.not_codigo, c.com_criadoem, c.not_codigo
                  FROM comentarios c
                  INNER JOIN noticias n ON c.not_codigo = n.not_codigo
                  WHERE c.usu_codigo = :usuario_id
                  AND c.com_criadoem > NOW() - INTERVAL 3 HOUR
                  ORDER BY c.com_criadoem DESC';
$stmtAtividades = $conexao->prepare($sqlAtividades);
$stmtAtividades->bindParam(':usuario_id', $_SESSION['id']);
$stmtAtividades->execute();
$atividades = $stmtAtividades->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | <?= htmlspecialchars($usuario->usu_nome) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
        }

        .card-custom {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            height: 510px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-pic-container {
            position: relative;
            width: 520px;
            height: 150px;
            margin: 0 auto;
        }

        .background-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php if (empty($usuario->usu_foto_de_fundo)) : ?>img/background.png<?php else : ?><?= htmlspecialchars($usuario->usu_foto_de_fundo) ?><?php endif; ?>');
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            border: 2px solid #333;

        }


        .profile-img {
            border: 5px solid white;
            position: absolute;
            bottom: 10px;
            left: 10px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            z-index: 2;
        }


        .camera-icon {
            position: absolute;
            bottom: 10px;
            left: 20%;
            z-index: 3;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 12px;
            border-radius: 50%;
            height: 45px;
            width: 45px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .camera-icon:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .camera-icon-background {
            position: absolute;
            bottom: 10px;
            right: 5%;
            z-index: 3;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 12px;
            border-radius: 50%;
            height: 45px;
            width: 45px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .camera-icon-background:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        @media (max-width: 768px) {
            .profile-pic-container {
                width: 100%;
                height: 200px;
                border-radius: 10px;
            }

            .profile-img {
                width: 100px;
                height: 100px;
            }

            .camera-icon {
                bottom: 10px;
                left: 24%;
            }

            .camera-icon-background {
                bottom: 10px;
                right: 2%;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #5c3c9b, #9c57e6);
            border: none;
            padding: 12px 20px;
            font-weight: bold;
            border-radius: 50px;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background: #9c57e6;
            transform: scale(1.05);
        }

        .activity-list {
            height: 300px;
            overflow-y: scroll;
            padding-right: 15px;
        }

        .activity-list li {
            list-style: none;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background: #f1f1f1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .activity-list .activity-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .activity-list li:hover {
            background: #eaeaea;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-access {
            border: 1px solid #9c57e6;
            color: #9c57e6;
            background: #fff;
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-access:hover {
            background: #9c57e6;
            color: #fff;
            transform: scale(1.05);
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }


        #profile-activities {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        #profile-activities .col-md-6 {
            flex: 1 1 calc(50% - 20px);
        }

        .card-custom {
            width: 100%;
        }

        .card-custom {
            min-height: 360px;
        }

        .modal-dialog {
            max-width: 90%;
            width: 900px;
            height: 80%;
            margin: 30px auto;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        .modal-header {
            background: #4b2a9b;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }

        .modal-body {
            display: flex;
            flex-grow: 1;
            padding: 0;
            overflow-y: auto;
        }

        .sidebar {
            background-color: #f4f4f4;
            padding: 20px;
            border-right: 2px solid #ddd;
        }

        .sidebar .nav-item {
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }

        .sidebar .nav-link.active {
            color: #9c57e6;
        }

        @media (max-width: 768px) {
            .sidebar .nav-link {
                text-align: center;
            }
        }

        .tab-content {
            padding: 20px;
            flex-grow: 1;
        }

        .modal-footer {
            background-color: #f4f4f4;
            border-top: 1px solid #ddd;
            padding: 20px;
            text-align: right;
        }

        .btn-primary {
            background-color: #9c57e6;
            border-color: #9c57e6;
        }

        .btn-primary:hover {
            background-color: #7b44b8;
            border-color: #7b44b8;
        }

        #uploadModal .modal-dialog,
        #uploadModalBackground .modal-dialog {
            max-width: 400px;
            height: 300px;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        #uploadModal .modal-content,
        #uploadModalBackground .modal-content {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        #uploadModal .modal-header,
        #uploadModalBackground .modal-header {
            background-color: #4b2a9b;
            color: #fff;
            padding: 10px;
            border-bottom: 1px solid #9c57e6;
            text-align: center;
        }

        #uploadModal .modal-title,
        #uploadModalBackground .modal-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        #uploadModal .modal-body,
        #uploadModalBackground .modal-body {
            padding: 15px;
            background-color: #f9f9f9;
            text-align: center;
        }

        #foto,
        #foto_de_fundo {
            width: 100%;
            padding: 8px;
            border: 2px dashed #9c57e6;
            background-color: #fff;
            margin-bottom: 15px;
            outline: none;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        #foto:hover,
        #foto_de_fundo:hover {
            border-color: #7b44b8;
        }

        #foto:focus,
        #foto_de_fundo:focus {
            border-color: #4b2a9b;
            box-shadow: 0 0 5px rgba(75, 42, 155, 0.5);
        }

        #uploadModal .modal-footer,
        #uploadModalBackground .modal-footer {
            background-color: #f4f4f4;
            text-align: center;
            padding: 10px;
        }

        #uploadModal .btn,
        #uploadModalBackground .btn {
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 5px;
            margin: 5px 0;
        }

        #uploadModal .btn-primary,
        #uploadModalBackground .btn-primary {
            background-color: #9c57e6;
            border-color: #9c57e6;
        }

        #uploadModal .btn-primary:hover,
        #uploadModalBackground .btn-primary:hover {
            background-color: #7b44b8;
            border-color: #7b44b8;
        }

        #uploadModal .btn-danger,
        #uploadModalBackground .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        #uploadModal .btn-danger:hover,
        #uploadModalBackground .btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
        }

        @media (max-width: 576px) {

            #uploadModal .modal-dialog,
            #uploadModalBackground .modal-dialog {
                max-width: 90%;
            }

            #uploadModal .modal-header,
            #uploadModal .modal-footer,
            #uploadModalBackground .modal-header,
            #uploadModalBackground .modal-footer {
                padding: 8px;
            }

            #uploadModal .modal-title,
            #uploadModalBackground .modal-title {
                font-size: 12px;
            }

            #foto {
                font-size: 12px;
            }

            #uploadModal .btn {
                font-size: 12px;
                padding: 6px 10px;
            }
        }


        .title-alteracao {
            font-size: 24px;
            font-weight: bold;
            color: #5c3c9b;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .title-alteracao span {
            color: #9c57e6;
        }

        @media (max-width: 768px) {
            .title-alteracao {
                font-size: 20px;
            }
        }

        form {
            text-align: center;
            justify-content: center;
            align-items: center;
            max-width: 500px;
            width: 500px;
            height: 420px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        .form-pic {
            text-align: center;
            justify-content: center;
            align-items: center;
            max-width: 500px;
            width: 500px;
            height: 260px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        form .form-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }

        form .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
            transition: border-color 0.3s ease;
            font-size: 17px;
        }

        form .form-control:focus {
            border-color: #4b2a9b;
            box-shadow: 0 0 5px rgba(75, 42, 155, 0.2);
            outline: none;
        }

        form #saveBtn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4b2a9b;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form #saveBtn:hover {
            background-color: #7b44b8;
        }

        form .container-saveBtn {
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 576px) {

            form,
            .form-pic {
                width: 94%;
                padding: 15px;
                margin: 10px;
            }

            form .form-label,
            form .form-control {
                font-size: 12px;
            }

            form #saveBtn {
                font-size: 12px;
                padding: 8px;
            }
        }

        .policy-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .policy-container h1,
        .policy-container h2 {
            color: #4b2a9b;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .policy-container h3 {
            color: #4b2a9b;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .policy-container p {
            text-align: justify;
            margin-bottom: 15px;
        }

        .policy-container ul {
            margin: 10px 0 20px 20px;
            padding-left: 20px;
            list-style-type: disc;
            color: #555;
        }

        .policy-container ul li {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .policy-container strong {
            color: #4b2a9b;
        }

        .policy-container a {
            color: #7b44b8;
            text-decoration: none;
            font-weight: bold;
        }

        .policy-container a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .policy-container {
                padding: 15px;
            }

            .policy-container h3 {
                font-size: 16px;
            }

            .policy-container ul li {
                font-size: 13px;
            }
        }

        .form-delete {
            text-align: center;
            height: 120px;
        }

        .delete-message {
            font-size: 20px;
            font-weight: bold;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .modal-backdrop.show {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
        }

        #confirmarModal .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
            height: 300px;
        }

        @media (max-width: 576px) {
            #confirmarModal .modal-dialog {
                width: 90%;
            }
        }

        #confirmarModal .modal-content {
            border-radius: 10px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #confirmarModal .modal-header {
            background: linear-gradient(45deg, #4b2a9b, #6933d1, #a02ae1);
            color: #fff;
            border-bottom: 1px solid #9c57e6;
            padding: 1rem;
        }

        #confirmarModal .modal-header .btn-close {
            color: #fff;
            filter: brightness(0.8);
            font-size: 1.2rem;
        }

        #confirmarModal .modal-body {
            padding: 1.5rem;
            font-size: 0.9rem;
            color: #333;
        }

        #confirmarModal .modal-body .generated-numbers {
            text-align: justify;
            font-size: 1.2rem;
        }

        #confirmarModal .modal-body input#usuario-numeros {
            width: 100%;
            font-size: 0.9rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            height: 40px;
        }

        #confirmarModal .modal-footer {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            background-color: #f9f9f9;
        }

        #confirmarModal .modal-footer .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }

        #confirmarModal .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
        }

        #confirmarModal .modal-footer .btn-danger {
            background-color: #d9534f;
            color: #fff;
            border: none;
        }

        #confirmarModal .modal-footer .btn-danger:hover {
            background-color: #c9302c;
        }

        .messageNumbers {
            text-align: left;
            font-weight: bold;
            color: #003366;
            margin-bottom: 1rem;
        }

        small {
            font-size: 12px;
            text-align: left;
        }

        .without-activities {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #4b2a9b;
            margin-bottom: 20px;
            font-family: monospace;
        }

        .title-programmer {
            font-size: 24px;
            font-weight: bold;
            color: #5c3c9b;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .text-programmer {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            text-align: justify;
        }

        .programador {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: 0.3s ease;
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
        }

        .social-medias {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 10px;
        }

        .social-medias a {
            color: #9c57e6;
            font-size: 24px;
            transition: 0.7s ease;
            text-decoration: none;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        .social-medias a:hover {
            color: #7b44b8;
            text-decoration: underline;
            transform: rotate(3deg);
        }

        .information {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: -10px 0 10px 0;
        }

        #exampleModalLabel {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .form-upload {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 200px;
        }

        @media (max-width: 576px) {
            #usu_nome {
                font-size: 19px;
            }

            #usu_email {
                font-size: 15px;
            }

            #usu_nivel {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow" style="background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#"><i class="bi bi-newspaper me-2 text-white"></i>INFONEWS</a>
            <button type="button" class="btn btn-primary ml-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-gear me-2"></i>
                Configurações
            </button>

        </div>
    </nav>

    <div class="container mt-5">
        <div class="row section" id="profile-activities">
            <div class="col-md-6">
                <div class="card-custom text-center">
                    <div class="profile-pic-container">
                        <div class="background-img"></div>
                        <?php if (empty($usuario->usu_foto)): ?>
                            <img class="profile-img" src="img/perfil-padrao.png" alt="Foto de perfil de <?= htmlspecialchars($usuario->usu_nome) ?>">
                        <?php else: ?>
                            <img class="profile-img" src="<?= htmlspecialchars($usuario->usu_foto) ?>" alt="Foto de perfil de <?= htmlspecialchars($usuario->usu_nome) ?>">
                        <?php endif; ?>
                        <i class="bi bi-camera-fill camera-icon"></i>
                        <i class="bi bi-camera-fill camera-icon-background"></i>
                    </div>
                    <h4 class="fw-bold" id="usu_nome"><?= htmlspecialchars($usuario->usu_nome) ?></h4>
                    <p class="information mb-2" id="usu_email"><?= htmlspecialchars($usuario->usu_email) ?>
                        <i class="bi bi-envelope-fill me-2"></i>
                    </p>
                    <p class="information" id="usu_nivel"> Seu nível aqui no InfoNews é <?= htmlspecialchars($usuario->usu_nivel) ?>
                        <?php if ($usuario->usu_nivel == 'admin') : ?>
                            <i class="bi bi-award-fill me-2" style="color:#FFD700;background-color:black;
                        padding:5px;border-radius:10px; box-shadow: 0 0 0.6em black;"></i>
                        <?php else: ?>
                            <i class="bi bi-award-fill me-2" style="color:#C0C0C0;background-color:black;
                        padding:5px;border-radius:10px; box-shadow: 0 0 0.6em black;"></i>
                        <?php endif; ?>
                    </p>
                    <a href="perfil.php?openModal=true&tab=screen1"><button class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square me-2"></i>
                            Atualizar perfil</button>
                    </a>
                    <?php if ($usuario->usu_nivel == 'admin') : ?>
                        <a href="indexnoticia.php" class="btn btn-primary w-100 mb-1 mt-2">
                            <i class="bi bi-journal-text me-2"></i>
                            Acesse o painel de controle</a>
                    <?php else : ?>
                        <a href="index.php" class="btn btn-primary w-100 mb-1 mt-2">
                            <i class="bi bi-newspaper me-2"></i>
                            Veja as notícias</a>
                    <?php endif; ?>
                    <a href="logout.php">
                        <button class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Sair
                        </button>
                    </a>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h4 class="text-center fw-bold">Atividades Recentes</h4>
                    <?php if (count($atividades) > 0) : ?>
                        <ul class="activity-list">
                            <?php foreach ($atividades as $atividade) : ?>
                                <li>
                                    <span class="activity-text">Comentou em "<a href="detalhesnoticias.php?id=<?= $atividade->not_codigo ?>" style="color:black;"><strong><?= htmlspecialchars($atividade->not_titulo) ?></strong></a>"</span>
                                    <a href="detalhesnoticias.php?id=<?= $atividade->not_codigo ?>" class="btn-access">Acessar</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="without-activities">
                            Não encontramos nenhuma atividade recente!
                        </p>
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-newspaper me-2"></i>
                            Veja as notícias e reaja!</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['msg'])) : ?>
                        <div class='alert alert-success' role='alert'>
                            <?= $_SESSION['msg']; ?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div class="modal fade mt-5" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Atualizar Foto de Perfil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="actionfoto.php" method="post" class="form-upload">
                            <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                            <input type="hidden" name="acao" value="editar-foto">
                            <label for="foto_de_fundo" class="form-label">Altere o link da sua foto de perfil</label>

                            <?php if (empty($usuario->usu_foto)): ?>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="usu_foto" id="foto" placeholder="Altere o link para a sua foto de perfil" required>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="usu_foto" id="foto" value="<?= $usuario->usu_foto ?>" required>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary w-100">Salvar Foto</button>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <form method="post" action="actionfoto.php" class="form-delete">
                            <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                            <input type="hidden" name="acao" value="excluir-foto">
                            <p class="delete-message">Deseja excluir a foto de perfil?</p>
                            <button type="submit" class="btn btn-danger w-100">Excluir Foto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade mt-5" id="uploadModalBackground" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Atualizar Foto de fundo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="actionfoto.php" method="post" class="form-upload">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->usu_codigo, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="acao" value="editar-foto-de-fundo">
                            <?php if (empty($usuario->usu_foto_de_fundo)): ?>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="foto_de_fundo" id="foto_de_fundo" placeholder="Insira o link para a sua foto de fundo" required>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <label for="foto_de_fundo" class="form-label">Altere o link da sua foto de fundo</label>
                                    <input
                                        type="url"
                                        class="form-control"
                                        name="foto_de_fundo"
                                        id="foto_de_fundo"
                                        value="<?= $usuario->usu_foto_de_fundo ?>"
                                        required
                                        pattern="https?://.+">
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary w-100">Salvar Foto</button>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <form method="post" action="actionfoto.php" class="form-delete">
                            <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                            <input type="hidden" name="acao" value="excluir-foto-de-fundo">
                            <p class="delete-message">Deseja excluir a foto de fundo?</p>
                            <button type="submit" class="btn btn-danger w-100">Excluir Foto de fundo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="bi bi-gear me-2"></i>
                            Configurações
                        </h5>
                        <button type="button" class="btn-close" style="color:#fff;" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 sidebar">
                                <ul class="nav flex-column" id="tabs">
                                    <div id="inicio">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab-1" data-bs-toggle="pill" href="#screen1">Verifique seus dados</a>
                                        </li>
                                    </div>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-2" data-bs-toggle="pill" href="#screen2">Políticas e Diretrizes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-3" data-bs-toggle="pill" href="#screen3">Desenvolvido por:</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 tab-content">
                                <div id="screen1" class="tab-pane fade show active">
                                    <p class="title-alteracao">Quer <span>alterar</span> algo?</p>
                                    <form method="post" action="actionfoto.php" class="form-pic">
                                        <?php
                                        $sql = 'SELECT usu_foto, usu_foto_de_fundo FROM usuarios WHERE usu_codigo = :id';
                                        $stmt = $conexao->prepare($sql);
                                        $stmt->bindParam(':id', $_SESSION['id']);
                                        $stmt->execute();
                                        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
                                        ?>
                                        <input type="text" name="id" value="<?= $usuario->usu_codigo ?>" hidden>
                                        <input type="text" name="acao" value="editar-foto" hidden>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Foto de Perfil:</label>
                                            <input type="text" class="form-control" name="foto" id="foto" value="<?= $usuario->usu_foto ?>">
                                        </div>
                                        <div clsas="mb-3">
                                            <label for="foto de fundo">Foto de Fundo:</label>
                                            <input type="text" class="form-control" name="foto_de_fundo" id="foto_de_fundo" value="<?= $usuario->usu_foto_de_fundo ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="saveBtn">Salvar</button>
                                    </form>
                                    <form method="post" action="actionusuario.php">
                                        <?php
                                        $sql = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
                                        $stmt = $conexao->prepare($sql);
                                        $stmt->bindParam(':id', $_SESSION['id']);
                                        $stmt->execute();
                                        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
                                        ?>
                                        <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                                        <input type="hidden" name="acao" value="editar-conta">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" class="form-control" name="nome" id="nome" value="<?= $usuario->usu_nome ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" value="<?= $usuario->usu_email ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="senha" class="form-label">Senha</label>
                                            <input type="password" id="senha" name="senha" class="form-control" value="<?= $usuario->usu_senha ?>">
                                            <small class="text-muted">Não se preocupe, sua senha está criptografada! Caso queira mudar, apenas apague e digite a nova.</small>
                                        </div>
                                        <?php if ($usuario->usu_nivel == 'admin') : ?>
                                            <input type="hidden" name="nivel" value="admin">
                                        <?php else : ?>
                                            <input type="hidden" name="nivel" value="usuario">
                                        <?php endif; ?>
                                        <div class="container-saveBtn">
                                            <button type="submit" class="btn btn-primary" id="saveBtn">Salvar</button>
                                        </div>
                                    </form>



                                </div>
                                <div id="screen2" class="tab-pane fade">
                                    <div class="policy-container">
                                        <h2>Políticas e Diretrizes do InfoNews</h2>
                                        <p>
                                            As Políticas e Diretrizes do InfoNews visam promover uma experiência segura, justa e transparente para todos os usuários. Ao utilizar o InfoNews, você concorda em seguir as seguintes regras e diretrizes:
                                        </p>

                                        <h3>1. Privacidade e Proteção de Dados</h3>
                                        <p>
                                            O InfoNews se compromete a proteger a privacidade de seus usuários. Coletamos e processamos dados pessoais de acordo com nossa <a href="#">Política de Privacidade</a>, que descreve como suas informações são coletadas, utilizadas e protegidas. Garantimos que seus dados serão utilizados apenas para personalizar a experiência no site e enviar notificações relevantes, e nunca serão compartilhados com terceiros sem seu consentimento.
                                        </p>

                                        <h3>2. Comportamento e Conduta</h3>
                                        <p>Os usuários devem interagir de forma respeitosa e ética em todas as áreas do InfoNews. É proibido:</p>
                                        <ul>
                                            <li>Publicar conteúdo discriminatório, ofensivo ou de ódio.</li>
                                            <li>Realizar comentários que incitem violência ou discriminação.</li>
                                            <li>Compartilhar informações falsas, enganosas ou prejudiciais.</li>
                                            <li>Usar a plataforma para promover atividades ilegais ou fraudulentas.</li>
                                            <li>Assediar ou atacar outros usuários, seja por meio de mensagens ou comentários.</li>
                                        </ul>
                                        <p>
                                            A plataforma se reserva o direito de remover qualquer conteúdo que viole essas diretrizes e de suspender ou bloquear usuários que desrespeitem essas regras.
                                        </p>

                                        <h3>3. Propriedade Intelectual</h3>
                                        <p>
                                            Todo o conteúdo disponibilizado no InfoNews, incluindo textos, imagens, vídeos e logotipos, é protegido por direitos autorais. O usuário não deve copiar, distribuir, modificar ou utilizar qualquer material do site sem a devida autorização, a menos que tenha sido explicitamente permitido. O InfoNews respeita os direitos autorais e espera que seus usuários façam o mesmo.
                                        </p>

                                        <h3>4. Uso de Conteúdo</h3>
                                        <p>
                                            O InfoNews permite que os usuários compartilhem e comentem notícias, artigos e outros conteúdos. Ao enviar conteúdo para a plataforma, o usuário concede ao InfoNews uma licença não exclusiva, transferível e sublicenciável para usar, exibir, modificar e distribuir esse conteúdo, sempre que necessário, dentro da plataforma e seus canais de comunicação.
                                        </p>

                                        <h3>5. Segurança da Conta</h3>
                                        <p>
                                            Cada usuário é responsável pela segurança de sua conta no InfoNews. Recomendamos que você utilize senhas fortes e únicas, além de ativar a verificação em duas etapas, caso disponível.
                                        </p>
                                        <h3>6. LGPD: Lei Geral de Proteção de Dados</h3>
                                        <p>
                                            O InfoNews está em conformidade com a Lei Geral de Proteção de Dados (LGPD), que regula o tratamento de dados pessoais no Brasil. Para mais informações, consulte em <a href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709.htm" style="text-decoration:underline;">GOV.BR</a>.</p>
                                        <a href="#inicio"><button type="button" class="btn btn-primary">Voltar ao início</button></a>

                                    </div>

                                </div>
                                <div id="screen3" class="tab-pane fade">
                                    <div class="programador">
                                        <h4 class="title-programmer">Programador por trás disso:</h4>
                                        <p class="text-programmer">Luís Felipe Giacomelli Rodrigues, estudante de programação, focado em Sistemas Web e Aplicativos híbridos. CEO da StartUp Giacomelli Dev's, uma empresa desenvolvedora de sistemas.</p>
                                        <img src="img/giacomellidevslogo.png" alt="Giacomelli Dev's" style="width: 200px; height: 200px; border-radius: 50%; margin-bottom: 20px; border: 2px solid #9c57e6;">
                                    </div>
                                    <div class="social-medias">
                                        <a href="https://github.com/smithgg415" target="_blank">GITHUB <i class="bi-github"></i> </a>
                                        <a href="https://www.linkedin.com/in/lu%C3%ADs-felipe-giacomelli-rodrigues-1449842a9/" target="_blank">LINKEDIN <i class="bi-linkedin"></i></a>
                                        <a href="https://www.instagram.com/lf.giacomelli/" target="_blank">INSTAGRAM <i class="bi-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const cameraIcon = document.querySelector('.camera-icon');
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        cameraIcon.addEventListener('click', function() {
            uploadModal.show();
        });
        const cameraIconBackground = document.querySelector('.camera-icon-background'); // Usando a classe correta
        const uploadModalBackground = new bootstrap.Modal(document.getElementById('uploadModalBackground'));
        cameraIconBackground.addEventListener('click', function() {
            uploadModalBackground.show();
        });



        const urlParams = new URLSearchParams(window.location.search);
        const openModal = urlParams.get('openModal');
        const tab = urlParams.get('tab');

        if (openModal === 'true') {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
                keyboard: false
            });
            myModal.show();

            document.querySelectorAll('.tab-pane').forEach(function(tabContent) {
                tabContent.classList.remove('show', 'active');
            });
            document.querySelectorAll('.nav-link').forEach(function(tabLink) {
                tabLink.classList.remove('active');
            });

            if (tab === 'screen2') {
                document.getElementById('tab-2').classList.add('active');
                document.getElementById('screen2').classList.add('show', 'active');
            } else {
                document.getElementById('tab-1').classList.add('active');
                document.getElementById('screen1').classList.add('show', 'active');
            }
        }

        function checkDeleteBtnVisibility(picSrc, deleteBtn) {
            if (!deleteBtn) return;
            deleteBtn.style.display = picSrc.includes('perfil-padrao.png') ? 'none' : 'inline-block';
        }
    </script>
</body>

</html>