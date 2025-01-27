<?php
session_start();

if (!isset($_SESSION["logado099"]) || $_SESSION["logado099"] !== true || $_SESSION["nivel"] !== "admin") {
    header("Location: index.php");
    exit;
}

require 'bd/conexao.php';
$conexao = conexao::getInstance();

$searchTerm = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = $_POST['search'] ?? '';
}

$sqlUsuariosAdmin = 'SELECT * FROM usuarios WHERE usu_nivel = "admin"';
if (!empty($searchTerm)) {
    $sqlUsuariosAdmin .= ' AND usu_nome LIKE :search';
}
$sqlUsuariosAdmin .= ' ORDER BY usu_nome';
$stmUsuariosAdmin = $conexao->prepare($sqlUsuariosAdmin);
if (!empty($searchTerm)) {
    $stmUsuariosAdmin->bindValue(':search', '%' . $searchTerm . '%');
}
$stmUsuariosAdmin->execute();
$usuariosAdmin = $stmUsuariosAdmin->fetchAll(PDO::FETCH_ASSOC);

$sqlUsuarios = 'SELECT * FROM usuarios WHERE usu_nivel = "usuario"';
if (!empty($searchTerm)) {
    $sqlUsuarios .= ' AND usu_nome LIKE :search';
}
$sqlUsuarios .= ' ORDER BY usu_nome';
$stmUsuarios = $conexao->prepare($sqlUsuarios);
if (!empty($searchTerm)) {
    $stmUsuarios->bindValue(':search', '%' . $searchTerm . '%');
}
$stmUsuarios->execute();
$usuarios = $stmUsuarios->fetchAll(PDO::FETCH_ASSOC);

$sqlNoticias = 'SELECT * FROM noticias';
if (!empty($searchTerm)) {
    $sqlNoticias .= ' WHERE not_titulo LIKE :search';
}
$sqlNoticias .= ' ORDER BY not_publicado_em DESC';
$stmNoticias = $conexao->prepare($sqlNoticias);
if (!empty($searchTerm)) {
    $stmNoticias->bindValue(':search', '%' . $searchTerm . '%');
}
$stmNoticias->execute();
$noticias = $stmNoticias->fetchAll(PDO::FETCH_ASSOC);
$sql = 'SELECT * FROM anuncios';
$stm = $conexao->prepare($sql);
$stm->execute();
$anuncios = $stm->fetchAll(PDO::FETCH_OBJ);
if (!empty($searchTerm)) {
    $sql .= ' WHERE anu_nome LIKE :search';
}
$sql .= ' ORDER BY anu_nome';
$stm = $conexao->prepare($sql);
if (!empty($searchTerm)) {
    $stm->bindValue(':search', '%' . $searchTerm . '%');
}
$stm->execute();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/stylepainel.css">
    <style>
        #painel {
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            padding: 40px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        #painel h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        #painel p {
            color: white;
            font-size: 1.2rem;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 30px 15px;
            }

            .container-fluid h1 {
                font-size: 2rem;
            }

            .container-fluid p {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container-fluid {
                padding: 25px 10px;
            }

            .container-fluid h1 {
                font-size: 1.8rem;
            }

            .container-fluid p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="container-fluid mt-1" id="painel">
            <h1 class="mb-3">Painel Administrativo <i class="bi bi-gear"></i>
            </h1>
            <p class="lead">Tenha controle total nas notícias, anúncios e usuários! <i class="bi bi-emoji-smile"></i>
            </p>
        </div>

        <div class="search-bar">
            <form action="" method="POST" class="d-flex justify-content-center">
                <input type="text" name="search" class="form-control w-50" placeholder="Buscar Notícias, Anúncios ou Usuários" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit" class="btn btn-primary ms-2 mr-3"><i class='bi-search'></i></button>

                <a href="index.php" class="btn btn-secondary ms-2">Home <i class='bi-house'></i></a>
            </form>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Notícias</h3>
                            <a href="addnoticia.php" class="btn btn-success btn-lg d-block mb-3">
                                <i class="bi bi-file-earmark-plus me-2"></i>Adicionar Notícia
                            </a>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($noticias) > 0): ?>
                                            <?php foreach ($noticias as $noticia): ?>
                                                <tr>
                                                    <td><?= $noticia['not_codigo'] ?></td>
                                                    <td>
                                                        <?= htmlspecialchars(strlen($noticia['not_titulo']) > 50 ? substr($noticia['not_titulo'], 0, 50) . '...' : $noticia['not_titulo']) ?>
                                                    </td>

                                                    <td class="row-12 d-flex justify-content-between">
                                                        <a href="editarnoticia.php?id=<?= $noticia['not_codigo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                        <form action="actionnoticia.php" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="acao" value="excluir">
                                                            <input type="hidden" name="id" value="<?= $noticia['not_codigo']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">Excluir</button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhuma notícia encontrada.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <p class="btn text-light mt-4 text-center bg-success">Quantidade de notícias cadastradas <b><?= count($noticias) ?></b></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Anúncios</h3>
                            <a href="addanuncio.php" class="btn btn-success btn-lg d-block mb-3">
                                <i class="bi bi-file-earmark-plus me-2"></i>Adicionar anúncio
                            </a>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($anuncios) > 0): ?>
                                            <?php foreach ($anuncios as $anuncio): ?>
                                                <tr>
                                                    <td><?= $anuncio->anu_codigo ?></td>
                                                    <td><?= htmlspecialchars($anuncio->anu_nome) ?></td>
                                                    <td>
                                                        <a href="editaranuncio.php?id=<?= $anuncio->anu_codigo; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                        <form action="actionanuncio.php" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="acao" value="excluir">
                                                            <input type="hidden" name="id" value="<?= $anuncio->anu_codigo; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhum anúncio encontrado.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <p class="btn text-light mt-4 text-center bg-success">Quantidade de anúncios cadastrados <b><?= count($anuncios) ?></b></p>
                        </div>
                    </div>
                </div>  
                <?php include "carrossel_anuncios.php"; ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Administradores</h3>
                            <a href="registeradm.php" class="btn btn-success btn-lg d-block mb-3">
                                <i class="bi bi-person-plus-fill me-2"></i>Novo Administrador
                            </a>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($usuariosAdmin) > 0): ?>
                                            <?php foreach ($usuariosAdmin as $usuario): ?>
                                                <tr>
                                                    <td><?= $usuario['usu_codigo'] ?></td>
                                                    <td><?= htmlspecialchars($usuario['usu_nome']) ?></td>
                                                    <td><?= htmlspecialchars($usuario['usu_email']) ?></td>
                                                    <td>
                                                        <?php if ($_SESSION['logado099'] && $_SESSION['id'] == $usuario["usu_codigo"]) : ?>
                                                            <div class="d-flex">
                                                                <a href="editarusuario.php?id=<?= $usuario['usu_codigo']; ?>" class="btn btn-warning btn-sm me-2">Editar</a>
                                                                <form action="actionusuario.php" method="POST" class="d-inline">
                                                                    <input type="hidden" name="acao" value="excluir">
                                                                    <input type="hidden" name="id" value="<?= $usuario['usu_codigo']; ?>">
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este administrador?')">Excluir</button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhum administrador encontrado.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <p class="btn text-light mt-4 text-center bg-success">Quantidade de administradores cadastrados <b><?= count($usuariosAdmin) ?></b></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Usuários</h3>
                            <a href="register.php" class="btn btn-success btn-lg d-block mb-3">
                                <i class="bi bi-person-plus me-2"></i>Adicionar Usuário
                            </a>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($usuarios) > 0): ?>
                                            <?php foreach ($usuarios as $usuario): ?>
                                                <tr>
                                                    <td><?= $usuario['usu_codigo'] ?></td>
                                                    <td><?= htmlspecialchars($usuario['usu_nome']) ?></td>
                                                    <td><?= htmlspecialchars($usuario['usu_email']) ?></td>
                                                    <td>
                                                        <?php #if (isset($_SESSION['logado099']) && $_SESSION['logado099'] && isset($_SESSION['id']) && $_SESSION['id'] == $usuario["usu_codigo"]) : 
                                                        ?>
                                                        <div class="d-flex">
                                                            <a href="editarusuario.php?id=<?= $usuario['usu_codigo']; ?>" class="btn btn-warning btn-sm me-2">Editar</a>
                                                            <form action="actionusuario.php" method="POST" class="d-inline">
                                                                <input type="hidden" name="acao" value="excluir">
                                                                <input type="hidden" name="id" value="<?= $usuario['usu_codigo']; ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                                                            </form>
                                                        </div>
                                                        <?php #endif; 
                                                        ?>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhum usuário encontrado.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <p class="btn text-light mt-4 text-center bg-success">Quantidade de usuários cadastrados
                                <b><?= count($usuarios) ?></b>
                            </p>
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