<?php

$sql = 'SELECT * FROM anuncios ORDER BY anu_codigo DESC';
$stm = $conexao->prepare($sql);
$stm->execute();
$anuncios = $stm->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrossel de Anúncios</title>
    <style>
        #anuncioCarousel img {
            width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .carousel-inner {
            position: relative;
            overflow: hidden;
        }

        .carousel-inner img {
            object-fit: cover;
        }

        @media (max-width: 768px) {
            #anuncioCarousel img {
                max-height: 200px;
            }
        }
    </style>
</head>

<body>
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
</body>

</html>