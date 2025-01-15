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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
        $sqlNoticia = 'SELECT * FROM noticias ORDER BY not_publicado_em DESC LIMIT 1';
        $stmNoticia = $conexao->prepare($sqlNoticia);
        $stmNoticia->execute();
        $noticia = $stmNoticia->fetch(PDO::FETCH_OBJ);
        ?>.last-news {
            margin-top: 65px;
            margin-left: 70px;
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
            padding-left: 20px;
            padding-bottom: 20px;
        }

        @media (max-width: 768px) {
            .last-news {
                margin-top: 55px;
                margin-left: 0;
                height: 500px;
            }
        }

        .last-news::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 15px;
            transition: all 0.3s ease-in-out;
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

        .last-news:hover {
            transform: scale(1.05);
        }

        .last-news:hover h1 {
            transform: translateY(-10px);
        }

        .last-news:hover::before {
            background: rgba(0, 0, 0, 0.6);
        }

        .last-news:hover h1::after {
            width: 100%;
        }

        .last-news h2 {
            letter-spacing: 2px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .last-news img {
            border-radius: 15px;
        }

        .square-news {
            display: flex;
            flex-direction: column;
            gap: 25px;
            padding: 0 20px;
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

        .news-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            transition: transform 0.3s ease-in-out;
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

        .card-text {
            font-size: 1rem;
            color: #666;
            margin-bottom: 20px;
        }

        .card-text:hover {
            color: #007bff;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .news-card:hover img {
            transform: scale(1.05);
        }

        .news-card:hover .card-title {
            color: #007bff;
        }

        .news-card .btn-primary {
            background-color: #007bff;
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
            background-color: #007bff;
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
            .square-news {
                padding: 0 15px;
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

        .click-to-more {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-weight: bold;
        }
        .last-published{
            background-color: #007bff;
            color: white;
            padding: 5px;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="bi bi-newspaper"></i> InfoNews
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if ($_SESSION['nivel'] === "admin") : ?>
                        <li class="nav-item">
                            <a href="indexnoticia.php" class="nav-link text-white">
                                <i class="bi bi-tools"></i> Controle
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="index.php" class="nav-link text-white">
                                <i class="bi bi-house-door"></i> Home
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['logado099']) : ?>
                        <li class="nav-item">
                            <a href="perfil.php" class="nav-link text-white">
                                <i class="bi bi-person"></i> Conta
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?php if ($_SESSION['logado099']) : ?>
                            <a href="logout.php" class="btn btn-sm ms-2">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </a>
                        <?php else : ?>
                            <a href="login.php" class="btn btn-sm text-light ms-2">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <a href="detalhesnoticias.php?id=<?= $noticia->not_codigo ?>" style="text-decoration:none;">
                    <div class="last-news">
                        <?php
                        $sqlUltimaNoticia = 'SELECT * FROM noticias ORDER BY not_publicado_em DESC LIMIT 1';
                        $stmUltimaNoticia = $conexao->prepare($sqlUltimaNoticia);
                        $stmUltimaNoticia->execute();
                        $ultimaNoticia = $stmUltimaNoticia->fetch(PDO::FETCH_OBJ);
                        $ultimaNoticiaCodigo = $ultimaNoticia->not_codigo;
                        ?>
                        <h1><?= $ultimaNoticia->not_titulo ?></h1>

                        <div class="last-published">
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
                                border-bottom: 3px solid #007bff;
                                transition: transform 0.3s ease-in-out;">
                                    <div class="click-to-more">
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


    <div class="container mt-5">
        <div class="news-container">
            <?php foreach ($noticias as $noticia) : ?>
                <div class="news-card">
                    <img src="<?= $noticia->not_imagem ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($noticia->not_titulo) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($noticia->not_conteudo), 0, 100) ?>...</p>
                        <a href="detalhesnoticias.php?id=<?= $noticia->not_codigo ?>" class="btn btn-primary">Leia Mais</a>
                    </div>
                    <div class="card-footer">
                        <small>Publicado em <?= date('d/m/Y H:i', strtotime($noticia->not_publicado_em)) ?></small>
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
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
                    <li><a href="#">Solicitação de Suporte</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Siga-nos</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Twitter</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2020 InfoNews Ltda. Todos os direitos reservados.</p>
            <a href="#" style="color: #a8a8ff; text-decoration: none;">Política de Privacidade</a>
        </div>
    </footer>
    <script>
        function atualizarDataHora() {
            var dataHoraAtual = new Date();

            var dataFormatada = dataHoraAtual.toLocaleDateString('pt-BR');
            var horaFormatada = dataHoraAtual.toLocaleTimeString('pt-BR');

            document.getElementById("dataHora").innerHTML = "Data: " + dataFormatada + " - Hora: " + horaFormatada;
        }

        setInterval(atualizarDataHora, 1000);

        atualizarDataHora();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>