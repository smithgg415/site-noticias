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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>

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
                    <!-- Controle/Admin -->
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


    <div class="container mt-5">
        <div class="hero mb-4">
            <h1 style="
            font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            letter-spacing:5px;
            background: linear-gradient(to right,  #ffad06,#fff ,#ff105f);
            background-clip: text; 
            color: transparent;
            ">Bem-vindo ao InfoNews</h1>
            <p>Descubra as últimas notícias e fique por dentro de tudo!</p>
            <p id="dataHora"></p>
        </div>

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