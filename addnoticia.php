<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Notícia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
}

.container {
    padding: 2rem 0;
}

.card {
    border: none;
    border-radius: 1rem;
}

.card-header {
    font-size: 1.8rem;
    font-weight: bold;
}

.form-control {
    border-radius: 0.5rem;
    box-shadow: none;
    border: 1px solid #ced4da;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    padding: 0.8rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 0.5rem;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    border: none;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #a71d2a;
}

.small {
    font-size: 0.9rem;
    color: #6c757d;
}

    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center rounded-top">
                        <h1>Cadastro de Notícia</h1>
                    </div>
                    <div class="card-body">
                        <form action="actionnoticia.php" method="post" id="form-noticia" enctype='multipart/form-data'>
                            <div class="form-group mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Informe o Título da Notícia" maxlength="100" required>
                                <small class="text-danger msg-erro msg-titulo"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="conteudo" class="form-label">Conteúdo</label>
                                <textarea class="form-control" id="conteudo" name="conteudo" rows="6" placeholder="Informe o Conteúdo da Notícia" required></textarea>
                                <small class="text-danger msg-erro msg-conteudo"></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="imagem" class="form-label">Imagem</label>
                                <input type="text" class="form-control" id="imagem" name="imagem" placeholder="URL da imagem" required>
                                <small class="text-danger msg-erro msg-imagem"></small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <input type="hidden" name="acao" value="incluir">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Gravar
                                </button>
                                <a href="index.php" class="btn btn-danger btn-lg">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/customnoticia.js"></script>
</body>
</html>
