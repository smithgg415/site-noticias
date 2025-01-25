<?php
session_start();

if (!isset($_SESSION['logado099'])) {
    $_SESSION['logado099'] = false;
    $_SESSION['id'] = 0;
    $_SESSION['nome'] = "Visitante";
    $_SESSION['nivel'] = "usuario";
}

require "bd/conexao.php";
$conexao = conexao::getInstance();

$sqlNoticias = 'SELECT * FROM noticias ORDER BY not_publicado_em DESC';
$stmNoticias = $conexao->prepare($sqlNoticias);
$stmNoticias->execute();
$noticias = $stmNoticias->fetchAll(PDO::FETCH_OBJ);
$sql = 'SELECT * FROM anuncios ORDER BY anu_codigo DESC';
$stm = $conexao->prepare($sql);
$stm->execute();
$anuncios = $stm->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="website icon" href="img/logoinfonews.jpg" type="png">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.6;
            padding-top: 70px;
            margin: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-transform: uppercase;
        }

        .navbar-nav .nav-link {
            color: white;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            transform: scale(1.05);
        }

        .navbar-toggler {
            border: none;
            color: white;
            font-size: 1.2rem;
        }

        .btn-sm {
            font-size: 0.875rem;
            padding: 0.4rem 0.8rem;
            border-radius: 30px;
            background-color: #007bff;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-sm:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }



        @keyframes gradiente-animado {
            0% {
                background-position-x: 0%;
            }

            100% {
                background-position-x: 100%;
            }
        }

        .news-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            padding: 0 20px;
            margin-bottom: 50px;
        }

        .news-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease-in-out;
            transform: translateY(0);
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .news-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 3px solid #007bff;
        }

        .news-card .card-body {
            padding: 20px;
            flex: 1;
        }

        .news-card .card-footer {
            padding: 10px 20px;
            background: #f7f7f7;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .news-card .btn {
            display: block;
            margin: 10px auto 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .news-card .btn-comentario {
            display: block;
            margin: 10px auto 0;
            padding: 10px 20px;
            background-color: #fff;
            color: white;
            border-radius: 10px;
            color: #007bff;
            border: 1px solid #007bff;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .news-card .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }


        .message-container {
            text-align: center;
            background-color: rgba(255, 255, 0, 0.4);
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            color: #000;
            text-decoration: none;
        }

        <?php
        $sqlNoticia = 'SELECT not_imagem FROM noticias ORDER BY not_publicado_em DESC LIMIT 1';
        $stmNoticia = $conexao->prepare($sqlNoticia);
        $stmNoticia->execute();
        $noticia = $stmNoticia->fetch(PDO::FETCH_OBJ);
        ?>@keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes borderAnimation {
            0% {
                clip-path: inset(0 100% 100% 0);
            }

            25% {
                clip-path: inset(0 0 100% 0);
            }

            50% {
                clip-path: inset(0 0 0 100%);
            }

            75% {
                clip-path: inset(100% 0 0 0);
            }

            100% {
                clip-path: inset(0 0 0 0);
            }
        }

        .last-news {
            position: relative;
            background-image: url('<?= $noticia->not_imagem ?>');
            background-size: cover;
            background-position: center;
            height: 730px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
            animation: slideInLeft 0.8s ease-out;
            overflow: hidden;
            margin: 65px 70px 0 70px;
        }

        .last-news::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            transition: all 0.3s ease-in-out;
        }

        .last-news::after {
            content: "";
            position: absolute;
            inset: 0;
            border: 3px solid transparent;
            border-radius: 15px;
            animation: none;
        }

        .last-news:hover::after {
            border-color: white;
            animation: borderAnimation 1.5s linear forwards;
        }

        .last-news:hover {
            transform: scale(1.05);
        }

        .last-news:hover::before {
            background: rgba(0, 0, 0, 0.6);
        }

        .last-news h1 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            z-index: 1;
            transition: transform 0.3s ease, color 0.3s ease;
            margin: 0;
            position: relative;
        }

        .last-news h1::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.7s ease-out;
        }

        .last-news:hover h1 {
            transform: translateY(-10px);
        }

        .last-news:hover h1::after {
            width: 100%;
        }

        .last-news h2 {
            letter-spacing: 2px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .last-published {
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            color: white;
            padding: 5px;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .square-news {
            display: flex;
            flex-direction: column;
            gap: 25px;
            padding: 0 20px;
            animation: slideInRight 0.8s ease-out;
        }

        .news-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            position: relative;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .news-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            transition: transform 0.3s ease-in-out;
        }

        .news-card:hover img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s ease-in-out;
        }

        .news-card:hover .card-title {
            color: #007bff;
        }

        .card-text {
            font-size: 1rem;
            color: #666;
            margin-bottom: 20px;
        }

        .card-text:hover {
            color: #007bff;
        }

        .news-card .btn-primary {
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
            text-align: center;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
        }

        .news-card .btn-primary:hover {
            background-color: #0056b3;
        }

        .news-card .click-to-more {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            font-weight: bold;
            text-transform: uppercase;
            color: white;
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 5px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;
            cursor: pointer;
        }

        .news-card .click-to-more:hover {
            background-color: #0056b3;
        }


        @media (max-width: 768px) {
            .news-card {
                width: 320px;
            }

            .square-news {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .last-news {
                height: 400px;
                font-size: 16px;
                margin: 30px 15px 0 15px;
            }

            .news-card img {
                height: 180px;
            }

            .card-body {
                padding: 15px;
            }

            .card-title {
                font-size: 1.2rem;
            }
        }


        .btn-login {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.8s linear;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-login i {
            margin-right: 0.5rem;
            transition: transform 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, red, #1C75FF);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-login:hover i {
            transform: translateX(5px);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            background-color: red;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.8s linear;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-logout i {
            margin-right: 0.5rem;
            transition: transform 0.3s ease;
        }

        .btn-logout:hover {
            background: linear-gradient(45deg, red, #1C75FF);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-logout:hover i {
            transform: translateX(5px);
        }

        .btn-controle {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(45deg, #28A745, #218838);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-controle i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-controle:hover {
            background: linear-gradient(45deg, #218838, #1E7E34);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-controle:hover i {
            transform: rotate(20deg);
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(45deg, #0056b3, #1C75FF);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-home i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-home:hover {
            background: linear-gradient(45deg, #0056b3, #1C75FF);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            color: #f8f9fa;
        }

        .btn-home:hover i {
            transform: scale(1.2);
        }

        .btn-account {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 600;
            background: linear-gradient(45deg, #0056b3, #1C75FF);

            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-account i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-account:hover {
            background: linear-gradient(45deg, #003580, #0056b3);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            color: #f8f9fa;
        }

        .btn-account:hover i {
            transform: scale(1.2);
        }

        .btn-account:active {
            transform: translateY(2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .nav-item {
            margin: 5px;
        }

        .titulo-noticias {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 2.5rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            text-align: center;
        }

        .titulo-noticias::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 4px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border-radius: 2px;
        }

        .titulo-noticias {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            position: fixed;
            right: 0;
            top: 0;
            padding-top: 1rem;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar.hidden {
            transform: translateX(100%);
        }

        .sidebar a {
            color: white;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 10px;
            text-decoration: none;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar i {
            font-size: 1.4rem;
            margin-right: 15px;
        }

        .main-content {
            margin-right: 250px;
            padding: 20px;
            transition: margin-right 0.3s;
            width: 100%;
        }

        .sidebar.hidden+.main-content {
            margin-right: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
            }
        }

        #topBar {
            position: fixed;
            top: 0;
            width: 100%;
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            color: white;
            padding: 12px 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            justify-content: space-between;
            z-index: 1000;
        }

        #topBar h3 {
            margin: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .toggle-btn i {
            transition: transform 0.3s ease, content 0.3s ease;
        }

        .toggle-btn:hover i::before {
            content: "\F12F";
            font-family: "bootstrap-icons";
        }

        .close-sidebar:hover i::before {
            content: "\F138";
            font-family: "bootstrap-icons";
        }
    </style>

</head>

<body>
    <div class="container-fluid" id="topBar">
        <h3>
            <i class="bi bi-envelope"></i>
            INFONEWS
        </h3>
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <?php
                $sqlUltimaNoticia = 'SELECT * FROM noticias ORDER BY not_publicado_em DESC LIMIT 1';
                $stmUltimaNoticia = $conexao->prepare($sqlUltimaNoticia);
                $stmUltimaNoticia->execute();
                $ultimaNoticia = $stmUltimaNoticia->fetch(PDO::FETCH_OBJ);
                $ultimaNoticiaCodigo = $ultimaNoticia->not_codigo;
                ?>
                <a href="detalhesnoticias.php?id=<?= $ultimaNoticia->not_codigo ?>" style="text-decoration:none;">
                    <div class="last-news">

                        <h1><?= $ultimaNoticia->not_titulo ?></h1>

                        <div class="last-published">
                            <i class="fa-solid fa-envelope-open"> </i>
                            Última notícia publicada!
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-5 mt-5">
                <div class="square-news m-5 mt-4">
                    <?php
                    $sql = 'SELECT * FROM noticias WHERE not_codigo != :not_codigo ORDER BY not_publicado_em DESC LIMIT 2';
                    $stm = $conexao->prepare($sql);
                    $stm->bindParam(':not_codigo', $ultimaNoticiaCodigo, PDO::PARAM_INT);
                    $stm->execute();
                    $someNews = $stm->fetchAll(PDO::FETCH_OBJ);
                    ?>
                    <?php foreach ($someNews as $news) : ?>
                        <a href="detalhesnoticias.php?id=<?= $news->not_codigo ?>" style="text-decoration:none;">
                            <div class="news-card">
                                <div class="news-card-image" style="background-image: url('<?= $news->not_imagem ?>');
                                background-size: cover;
                                background-position: center;
                                height: 220px;
                                border-top-left-radius: 15px;
                                border-top-right-radius: 15px;
                                border-bottom: 1px solid #007bff;
                                transition: transform 0.3s ease-in-out;">
                                    <div class="click-to-more">
                                        <i class="fa-solid fa-circle-info"> </i>
                                        Clique para mais
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($news->not_titulo) ?></h5>
                                </div>
                            </div>
                        </a>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <h2 class="titulo-noticias">Previsão do tempo:</h2>
    <?php include "clima-atual.html"; ?>
    <?php include "carrossel_anuncios.php"; ?>
    <h2 class="titulo-noticias">Últimas notícias:</h2>

    <div class="container mt-5">
        <div class="news-container">
            <?php foreach ($noticias as $noticia) : ?>
                <div class="news-card">
                    <a href="detalhesnoticias.php?id=<?= $noticia->not_codigo ?>" style="text-decoration:none;">
                        <img src="<?= $noticia->not_imagem ?>" alt="<?= $noticia->not_titulo ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($noticia->not_titulo) ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($noticia->not_conteudo), 0, 100) ?>...</p>
                            <a href="detalhesnoticias.php?id=<?= $noticia->not_codigo ?>" class="btn btn-primary">Leia Mais</a>
                        </div>
                        <div class="card-footer">
                            <small> Publicado em <?= date('d/m/Y', strtotime($noticia->not_publicado_em)) . " às " . date('H:i', strtotime($noticia->not_publicado_em)) ?> | Por InfoNews</small>
                        </div>

                        <div class="card-footer mt-3">
                            <?php if ($_SESSION['logado099']) : ?>
                                <button class="btn-comentario btn-success" data-bs-toggle="collapse" data-bs-target="#comentarios<?= $noticia->not_codigo ?>">Comentar</button>
                                <div class="collapse" id="comentarios<?= $noticia->not_codigo ?>">
                                    <form action="actioncomentario.php" method="POST">
                                        <input type="hidden" name="acao" value="incluir">
                                        <input type="hidden" name="not_codigo" value="<?= $noticia->not_codigo ?>">
                                        <textarea class="form-control mt-2" name="conteudo" rows="3" placeholder="Escreva seu comentário..."></textarea>
                                        <button type="submit" class="btn btn-success mt-2"><i class='bi-send'> </i>Enviar</button>
                                    </form>
                                </div>
                            <?php else : ?>
                                <div class="message-container">
                                    <a href="login.php" class="login-link"><i class="bi bi-box-arrow-in-right icon"></i> <b>Faça login</b> para comentar.</a>
                                </div>
                            <?php endif; ?>
                    </a>
                </div>
        </div>
    <?php endforeach; ?>
    </div>

    <footer>
        <div class="footer-header">
            <h2>Quer se tornar nosso parceiro?</h2>
            <p>Se você está interessado em parcerias e gostaria de saber mais, um de nossos consultores está pronto para ajudar.</p>
            <a href="quemsomos.php" style="text-decoration:none;"><button>Saiba mais</button></a>
        </div>

        <div class="footer-container">
            <div class="footer-section">
                <h3>Parcerias</h3>
                <ul>
                    <li><a href="#">Sites</a></li>
                    <li><a href="#">Redes Sociais</a></li>
                    <li><a href="#">Branding</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Sobre</h3>
                <ul>
                    <li><a href="#">Nossa Missão</a></li>
                    <li><a href="#">Nossos Trabalhos</a></li>
                    <li><a href="#">Carreiras</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Suporte</h3>
                <ul>
                    <li><a href="perfil.php?openModal=true&tab=screen2">Políticas e Diretrizes</a></li>
                    <li><a href="#">Solicitação de Suporte</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Siga-nos</h3>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Twitter</a></li>
                </ul>
            </div>

            <div class="logo-infonews justify-content-center">
                <img src="img/logoinfonews.jpg" alt="Logo InfoNews" width="150" height="150">
                <img src="img/giacomellidevslogo.png" alt="" height="150" width="150" width="150">
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 InfoNews Ltda. Todos os direitos reservados.</p>
            <a href="#" style="color: #a8a8ff; text-decoration: none;">Política de Privacidade</a>
        </div>
    </footer>
    <div class="sidebar hidden" id="sidebar">
        <a href="#" onclick="toggleSidebar()" class="close-sidebar">
            <i class="bi bi-x-lg"></i> Fechar
        </a>
        <hr style="color: white;">
        <a href="index.php" class="btn-home mt-3"><i class="bi bi-house-door"></i> Home</a>
        <?php if ($_SESSION['nivel'] === "admin") : ?>
            <a href="indexnoticia.php" class="btn-controle"><i class="bi bi-tools"></i> Controle</a>
        <?php endif; ?>
        <?php if ($_SESSION['logado099']) : ?>
            <a href="perfil.php" class="btn-account"><i class="bi bi-person"></i> Conta</a>
            <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Sair</a>
        <?php else : ?>
            <a href="login.php" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("hidden");
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>
</body>

</html>