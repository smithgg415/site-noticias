<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quem Somos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, #4b2a9b, #6933d1);
            color: #fff;
            padding: 50px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .hero p {
            font-size: 1.2rem;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .about-section {
            padding: 40px 20px;
        }

        .about-card {
            border: none;
            background: #f8f9fa;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .about-card:hover {
            transform: scale(1.05);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4b2a9b, #6933d1);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .footer-header h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
    </style>
</head>

<body>
    <div class="hero">
        <h1>Quem Somos?</h1>
        <p>Estudante de programação e dono da StartUp Giacomelli Dev's</p>
    </div>

    <section class="about-section container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="about-card p-4">
                    <div class="icon-circle">
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                    <h4></h4>
                    <p>Proporcionar produtos e serviços de qualidade que inspirem nossos clientes a alcançar seus objetivos.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="about-card p-4">
                    <div class="icon-circle">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                    <h4>Visão</h4>
                    <p>Ser referência no mercado, promovendo inovação e excelência em tudo que fazemos.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="about-card p-4">
                    <div class="icon-circle">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h4>Valores</h4>
                    <p>Integridade, inovação, sustentabilidade e compromisso com a satisfação dos clientes.</p>
                </div>
            </div>
        </div>
    </section>
    <?php include "footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>