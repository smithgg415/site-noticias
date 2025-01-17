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
            height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-pic-container {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-pic-container img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 5px solid #007bff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .camera-icon {
            position: absolute;
            bottom: 10px;
            right: 15%;
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


        .activity-list {
            height: 300px;
            overflow-y: auto;
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
            transition: 0.3s ease;
        }

        .activity-list li:hover {
            background: #eaeaea;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .activity-list .activity-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
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
            min-height: 350px;
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

        #uploadModal .modal-dialog {
            max-width: 400px;
            height: 300px;
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #uploadModal .modal-content {
            margin-top: 40px;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        #uploadModal .modal-header {
            background-color: #4b2a9b;
            color: #fff;
            padding: 10px;
            border-bottom: 2px solid #9c57e6;
        }

        #uploadModal .modal-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        #uploadModal .modal-body {
            flex-grow: 1;
            padding: 10px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        #fileInput {
            width: 100%;
            padding: 8px;
            border: 2px dashed #9c57e6;
            background-color: #fff;
            outline: none;
            transition: all 0.3s ease-in-out;
            text-align: center;
        }

        #fileInput:hover {
            border-color: #7b44b8;
        }

        #fileInput:focus {
            border-color: #4b2a9b;
            box-shadow: 0 0 5px rgba(75, 42, 155, 0.5);
        }

        #uploadModal .modal-footer {
            background-color: #f4f4f4;
            border-top: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }

        #uploadModal .btn {
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 5px;
        }

        #uploadModal .btn-primary {
            background-color: #9c57e6;
            border-color: #9c57e6;
        }

        #uploadModal .btn-primary:hover {
            background-color: #7b44b8;
            border-color: #7b44b8;
        }

        #uploadModal .btn-danger {
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        #uploadModal .btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
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
            form {
                width: 90%;
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
            font-family: Verdana, Geneva, Tahoma, sans-serif;
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
            font-size: 20px;
            font-weight: bold;
            color: #333;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: -10px 0 10px 0;
        }

        #exampleModalLabel {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow" style="background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#"><i class="bi bi-newspaper me-2 text-white"></i>INFONEWS</a>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
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
                        <img id="profile-pic" src="perfil-padrao.png" alt="Foto de perfil">
                        <i class="bi bi-camera-fill camera-icon"></i>
                    </div>
                    <h4 class="fw-bold"><?= htmlspecialchars($usuario->usu_nome) ?></h4>
                    <p class="information mb-2"><?= htmlspecialchars($usuario->usu_email) ?>
                        <i class="bi bi-envelope-fill me-2"></i>
                    </p>
                    <p class="information"> Seu nível aqui no InfoNews é <?= htmlspecialchars($usuario->usu_nivel) ?>
                        <?php if ($usuario->usu_nivel == 'admin') : ?>
                            <i class="bi bi-award-fill me-2" style="color:#FFD700;background-color:black;
                        padding:5px;border-radius:10px; box-shadow: 0 0 1em black;"></i>
                        <?php else: ?>
                            <i class="bi bi-award-fill me-2" style="color:#C0C0C0;background-color:black;
                        padding:5px;border-radius:10px; box-shadow: 0 0 1em black;"></i>
                        <?php endif; ?>

                    </p>
                    <a href="editarusuario.php?id=<?= $usuario->usu_codigo ?>"><button class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square me-2"></i>
                            Atualizar perfil</button></a>
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
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Atualizar Foto de Perfil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" class="form-control" id="fileInput" accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteBtn">Excluir foto</button>
                        <button type="button" class="btn btn-primary" id="uploadBtn">Salvar Foto</button>
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
                                    <form action="actionusuario.php" method="post" class="form-delete">
                                        <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                                        <p class="delete-message">Deseja excluir sua conta?</p>
                                        <button type="button" class="btn btn-danger" onclick="confirmarDecisao();">
                                            <i class="bi bi-trash-fill ms-2"></i>
                                            Excluir conta</button>

                                        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                                            <div class="modal-dialog custom-dialog">
                                                <div class="modal-content custom-content">
                                                    <div class="modal-header custom-header">
                                                        <h5 class="modal-title" id="confirmarModalLabel">Confirmar Exclusão de Conta</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body custom-body">
                                                        <div class="row">
                                                            <p class="messageNumbers">Por favor, digite os 8 números abaixo para confirmar a exclusão da conta:</p>
                                                            <p class="generated-numbers"></p>
                                                        </div>
                                                        <input type="text" id="usuario-numeros" placeholder="Digite os 8 números" class="form-control">
                                                    </div>
                                                    <div class="modal-footer custom-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        <button type="button" class="btn btn-danger" onclick="verificarNumeros();">Confirmar Exclusão
                                                            <i class="bi bi-trash-fill ms-2"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
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
        window.onload = function() {
            const savedPic = localStorage.getItem('profilePic') || 'img/perfil-padrao.png';
            updateProfilePicture(savedPic);

            const cameraIcon = document.querySelector('.camera-icon');
            const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            cameraIcon.addEventListener('click', function() {
                uploadModal.show();
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
        };

        function updateProfilePicture(src) {
            const profilePic = document.getElementById('profile-pic');
            const deleteBtn = document.getElementById('deleteBtn');

            if (profilePic) {
                profilePic.src = src;
                checkDeleteBtnVisibility(src, deleteBtn);
            }
        }

        function checkDeleteBtnVisibility(picSrc, deleteBtn) {
            if (!deleteBtn) return;
            deleteBtn.style.display = picSrc.includes('perfil-padrao.png') ? 'none' : 'inline-block';
        }

        const uploadBtn = document.getElementById('uploadBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function() {
                const fileInput = document.getElementById('fileInput');
                if (!fileInput) return;

                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        localStorage.setItem('profilePic', reader.result);
                        updateProfilePicture(reader.result);

                        const modalElement = document.getElementById('uploadModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert("Por favor, selecione uma imagem.");
                }
            });
        }

        const deleteBtn = document.getElementById('deleteBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                localStorage.removeItem('profilePic');
                updateProfilePicture('img/perfil-padrao.png');
            });
        }

        function confirmarDecisao() {
            const numerosSorteados = gerarNumerosAleatorios().join('');
            localStorage.setItem('numerosSorteados', numerosSorteados);

            const numerosContainer = document.querySelector('#confirmarModal .generated-numbers');
            if (numerosContainer) {
                numerosContainer.textContent = numerosSorteados.split('').join(' ');
            }

            const modal = new bootstrap.Modal(document.getElementById('confirmarModal'));
            modal.show();
        }

        function gerarNumerosAleatorios() {
            let numeros = [];
            for (let i = 0; i < 8; i++) {
                numeros.push(Math.floor(Math.random() * 10));
            }
            return numeros;
        }

        function verificarNumeros() {
            const numerosSorteados = localStorage.getItem('numerosSorteados');
            const numerosInseridos = document.getElementById('usuario-numeros').value;

            if (numerosSorteados === numerosInseridos) {
                alert("Conta excluída com sucesso!");
                document.querySelector('.form-delete').submit(); // Submete o formulário de exclusão
            } else {
                alert("Os números inseridos estão incorretos. Tente novamente.");
            }
        }
    </script>
</body>

</html>