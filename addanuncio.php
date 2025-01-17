<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Anúncio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background-color: transparent;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
        }

        .close-button:hover {
            color: #dc3545;
        }

        .ad-section {
            position: relative;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light text-center">
                        <h1 class="h3">Cadastro de Anúncio</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <form action="actionanuncio.php" method="post" id="form-anuncio" enctype='multipart/form-data'>
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">
                                            <i class="fa-regular fa-file-word"></i> Nome do anúncio
                                        </label>
                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Informe um nome para o anúncio" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="imagem" class="form-label">
                                            <i class="fas fa-image me-2"></i>URL da Imagem
                                        </label>
                                        <input type="text" class="form-control" id="imagem" name="imagem" placeholder="Informe a URL da imagem" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="linkacesso" class="form-label">
                                            <i class="fas fa-link me-2"></i>Link de Acesso
                                        </label>
                                        <input type="text" class="form-control" id="linkacesso" name="linkacesso" placeholder="Informe o Link de Acesso" required>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <input type="hidden" name="acao" value="incluir">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Adicionar Anúncio
                                        </button>
                                        <a href="indexnoticia.php" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Voltar
                                        </a>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-center">
                                <div class="ad-section border rounded p-4 w-100 text-center bg-light" id="adSection">
                                    <button class="close-button" onclick="fecharAnuncio()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <h2 class="h5" style="font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;letter-spacing: 2px;
                                    background:linear-gradient(to right, #000, #6c757d , #6c757d, #000);
                                    background-clip: text;
                                    color:transparent;
                                    
                                    ">Desenvolvedora de Software</h2>
                                    <img src="https://nutriflow.netlify.app/logos/giacomellilogo.png" alt="Pré-visualização da Imagem" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>