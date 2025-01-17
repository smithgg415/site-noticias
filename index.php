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
    <link rel="website icon" href="img/logoinfonews.jpg" type="png">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        
        <?php
        $sqlNoticia = 'SELECT not_imagem FROM noticias ORDER BY not_publicado_em DESC LIMIT 1';
        $stmNoticia = $conexao->prepare($sqlNoticia);
        $stmNoticia->execute();
        $noticia = $stmNoticia->fetch(PDO::FETCH_OBJ);
        ?>@keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
                transition: 0.8s linear;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
                transition: 0.8s linear;
            }
        }

        .last-news {
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
            animation: slideInLeft 0.8s ease-out;
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
                            <a href="indexnoticia.php" class="btn-controle">
                                <i class="bi bi-tools"></i> Controle
                            </a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a href="index.php" class="btn-home">
                                <i class="bi bi-house-door"></i> Home
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['logado099']) : ?>
                        <li class="nav-item">
                            <a href="perfil.php" class="btn-account">
                                <i class="bi bi-person"></i> Conta
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?php if ($_SESSION['logado099']) : ?>
                            <a href="logout.php" class="btn-login">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </a>
                        <?php else : ?>
                            <a href="login.php" class="btn-login">
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
                                border-bottom: 1px solid #007bff;
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
    <div class="container mt-4" id="anuncios">
        <div id="anuncioCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
            <div class="carousel-inner">
                <?php foreach ($anuncios as $index => $anuncio) : ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="<?= $anuncio->anu_imagem ?>"
                            class="d-block w-100"
                            alt="Anúncio"
                            style="object-fit: cover; height: auto; max-height: 300px;">
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#anuncioCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#anuncioCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button> -->
        </div>
    </div>


    <h2 class="titulo-noticias">Últimas notícias:</h2>

    <div class="container mt-5">
        <div class="news-container">
            <?php foreach ($noticias as $noticia) : ?>
                <div class="news-card">
                    <a href="detalhesnoticias.php?id=<?= $noticia->not_codigo ?>" style="text-decoration:none;">
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
                    </a>
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

            <div class="logo-infonews justify-content-center">
                <img src="img/logoinfonews.jpg" alt="Logo InfoNews" width="150" height="150">
                <img src="https://nutriflow.netlify.app/logos/giacomellilogo.png" alt="" height="150" width="150" width="150">
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 InfoNews Ltda. Todos os direitos reservados.</p>
            <a href="#" style="color: #a8a8ff; text-decoration: none;">Política de Privacidade</a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>