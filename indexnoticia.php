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

// Administradores
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

// Usuários
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

// Notícias
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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/stylepainel.css">
</head>

<body>
    <?php include_once "header.php"; ?>

    <div class="container mt-5">
        <div class="content-header">
            <h1 class="mb-3">Painel Administrativo</h1>
            <p class="lead">Gerencie os administradores, usuários e notícias de forma eficiente</p>
        </div>

        <div class="search-bar">
            <form action="" method="POST" class="d-flex justify-content-center">
                <input type="text" name="search" class="form-control w-50" placeholder="Buscar Notícias ou Usuários" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit" class="btn btn-primary ms-2"><i class='bi-search'></i></button>
                <a href="index.php" class="btn btn-secondary ms-2">Home <i class='bi-house'></i></a>
            </form>
        </div>

        <div class="row g-4">
            <!-- Administradores -->
            <div class="col-lg-4">
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
                                                    <a href="editarusuario.php?id=<?= $usuario['usu_codigo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                    <form action="actionusuario.php" method="POST" style="display: inline-block;">
                                                        <input type="hidden" name="acao" value="excluir">
                                                        <input type="hidden" name="id" value="<?= $usuario['usu_codigo']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este administrador?')">Excluir</button>
                                                    </form>
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

            <!-- Notícias -->
            <div class="col-lg-4">
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
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($noticias) > 0): ?>
                                        <?php foreach ($noticias as $noticia): ?>
                                            <tr>
                                                <td><?= $noticia['not_codigo'] ?></td>
                                                <td><?= htmlspecialchars($noticia['not_titulo']) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($noticia['not_publicado_em'])); ?></td>
                                                <td>
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

            <!-- Usuários -->
            <div class="col-lg-4">
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
                                                <td >
                                                    <a href="editarusuario.php?id=<?= $usuario['usu_codigo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                    <form action="actionusuario.php" method="POST" style="display: inline-block;">
                                                        <input type="hidden" name="acao" value="excluir">
                                                        <input type="hidden" name="id" value="<?= $usuario['usu_codigo']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                                                    </form>
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
    <footer class="text-center mt-5">
        <p>© 2025 Painel Administrativo. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>