<?php
session_start();

// Verifique se o usuário está logado
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 5% auto;
            padding: 40px;
            font-size: 16px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            color: #495057;
            margin-bottom: 20px;
        }

        textarea {
            min-height: 150px;
        }

        .input-group i {
            color: #007bff;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 700;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        button:active {
            transform: translateY(0);
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .alert i {
            margin-right: 10px;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            h1 {
                font-size: 1.8rem;
            }

            button {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Editar Notícia</h1>

        <?php if (isset($_SESSION['mensagem'])): ?>
            
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <form method="POST" action="actionnoticia.php">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" value="<?= $id; ?>">

            <div class="form-group">
                <label for="titulo"><i class="fas fa-heading"></i> Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['not_titulo']); ?>" required>
            </div>

            <div class="form-group">
                <label for="conteudo"><i class="fas fa-pencil-alt"></i> Conteúdo:</label>
                <textarea id="conteudo" name="conteudo" class="form-control" required><?= htmlspecialchars($noticia['not_conteudo']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="imagem"><i class="fas fa-image"></i> URL da Imagem:</label>
                <input type="text" id="imagem" name="imagem" class="form-control" value="<?= htmlspecialchars($noticia['not_imagem']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="indexnoticia.php" class="btn-back"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
