<?php
session_start();

if (!isset($_SESSION["logado099"]) || $_SESSION['nivel'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require 'bd/conexao.php';
$conexao = conexao::getInstance();

// Obter o ID da notícia
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $sql = 'SELECT * FROM noticias WHERE not_codigo = :id';
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia) {
        $_SESSION['mensagem'] = "Notícia não encontrada.";
        header("Location: minhasnoticias.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia</title>
    <!-- Bootstrap CSS -->
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fa;
        }

        .container {
            margin-top: 50px;
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .image-preview {
            border: 1px dashed #ccc;
            border-radius: 10px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #f9f9f9;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        #btn-save {
            background-color: #4b2a9b;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="form-section">
                    <h2 class="text-center">Editar Notícia</h2>
                    <?php if (isset($_SESSION['mensagem'])): ?>
                        <div class="alert alert-warning">
                            <?= $_SESSION['mensagem']; ?>
                        </div>
                        <?php unset($_SESSION['mensagem']); ?>
                    <?php endif; ?>
                    <form method="POST" action="actionnoticia.php">
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" name="id" value="<?= $id; ?>">

                        <div class="form-group mb-3">
                            <label for="titulo"><i class="fas fa-heading"></i> Título:</label>
                            <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['not_titulo']); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="conteudo"><i class="fas fa-pencil-alt"></i> Conteúdo:</label>
                            <textarea id="conteudo" name="conteudo" class="form-control" rows="5" required><?= htmlspecialchars($noticia['not_conteudo']); ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagem"><i class="fas fa-image"></i> URL da Imagem:</label>
                            <input type="text" id="imagem" name="imagem" class="form-control" value="<?= htmlspecialchars($noticia['not_imagem']); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="categoria"><i class="fas fa-list"></i> Categoria:</label>
                            <select name="categoria" class="form-control">
                                <option value="<?= $noticia['not_categoria']; ?>"><?= $noticia['not_categoria']; ?></option>
                                <option value="Política">Política</option>
                                <option value="Economia">Economia</option>
                                <option value="Esportes">Esportes</option>
                                <option value="Entretenimento">Entretenimento</option>
                                <option value="Saúde">Saúde</option>
                                <option value="Tecnologia">Tecnologia</option>
                                <option value="Ciência">Ciência</option>
                                <option value="Mundo">Mundo</option>
                            </select>
                        </div>
                        <button type="submit" id="btn-save" class="btn w-100 mb-3">Salvar Alterações</button>
                        <a href="indexnoticia.php" class="btn btn-secondary w-100"><i class="fas fa-arrow-left"></i> Voltar</a>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="image-preview">
                    <?php if (!empty($noticia['not_imagem'])): ?>
                        <img src="<?= htmlspecialchars($noticia['not_imagem']); ?>" alt="Pré-visualização da imagem">
                    <?php else: ?>
                        <span>Nenhuma imagem disponível</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>