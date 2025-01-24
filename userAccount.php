<?php
require 'bd/conexao.php'; 

if (!isset($_GET['id'])) {
    die("Usuário não especificado.");
}

$usu_codigo = intval($_GET['id']);

$conexao = conexao::getInstance();

$sqlUsuario = 'SELECT * FROM usuarios u WHERE u.usu_codigo = :id';
$stmt = $conexao->prepare($sqlUsuario);
$stmt->bindParam(':id', $usu_codigo, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}
$sqlAtividades = "SELECT c.com_conteudo, n.not_titulo, n.not_codigo, c.com_criadoem 
                  FROM comentarios c
                  INNER JOIN noticias n ON c.not_codigo = n.not_codigo
                  WHERE c.usu_codigo = :usuario_id
                  AND c.com_criadoem > NOW() - INTERVAL 3 HOUR
                  ORDER BY c.com_criadoem DESC";

$stmtAtividades = $conexao->prepare($sqlAtividades);
$stmtAtividades->bindParam(':usuario_id', $usu_codigo, PDO::PARAM_INT);
$stmtAtividades->execute();
$atividades = $stmtAtividades->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($usuario['usu_nome']) ?> - InfoNews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="website icon" type="png" href="img/logoinfonews.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
        }

        .perfil-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            height: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            max-width: 500px;
            margin-bottom: 20px;
        }

        .perfil-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .perfil-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
        }

        .perfil-header .perfil-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .perfil-header h1 {
            color: #333;
            font-size: 2rem;
            margin: 0;
        }

        .perfil-header p {
            font-size: 1.1rem;
            color: #555;
            margin-top: 5px;
        }

        .atividades-list {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        .atividades-list li {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 12px;
            transition: background 0.3s ease;
        }

        .atividades-list li:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .atividades-list li h3 {
            color: #ffc107;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .atividades-list li p {
            font-size: 1rem;
            color: #333;
        }

        .atividades-list li small {
            font-size: 0.9rem;
            color: #777;
        }

        footer {
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .perfil-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .perfil-header img {
                width: 100px;
                height: 100px;
            }

            .perfil-header h1 {
                font-size: 1.8rem;
            }
        }

        .without-activities {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #4b2a9b;
            margin-bottom: 20px;
            font-family: monospace;
        }

        .activity-list {
            height: 300px;
            overflow-y: scroll;
            padding-right: 15px;
        }

        .activity-list li {
            list-style: none;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            background: #f1f1f1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .activity-list .activity-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .activity-list li:hover {
            background: #eaeaea;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-access {
            border: 1px solid #9c57e6;
            color: #9c57e6;
            background: #fff;
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-access:hover {
            background: #9c57e6;
            color: #fff;
            transform: scale(1.05);
        }

        .perfil-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;

        }

        .perfil-footer h3 {
            font-size: 1.2rem;
            color: #333;
        }

        @media (max-width: 768px) {
            .perfil-container {
                max-width: 340px;
            }
        }

        .profile-pic {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;

        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?php if (empty($usuario['usu_foto_de_fundo'])) : ?>img/background.png<?php else : ?><?= htmlspecialchars($usuario['usu_foto_de_fundo']) ?><?php endif; ?>');
            background-size: cover;
            background-position: center;
            z-index: 0;
            border: 2px solid #333;
            border-radius: 10px;
        }

        .perfil-pic {
            margin: 10px;
            left: 20px;
            position: relative;
            z-index: 1;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
        }

        .perfil-info {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            text-align: center;
            color: #333;
            padding: 20px 10px;
            background: linear-gradient(45deg, #f3f3f3, #e2e2e2);
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .perfil-info h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #4b2a9b;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .perfil-info p {
            font-size: 1.1rem;
            color: #555;
            margin-top: 5px;
            margin-bottom: 0;
        }

        .perfil-info i {
            margin-right: 5px;
            color: #4b2a9b;
        }

        .perfil-info:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #ffffff, #e2e2e2);
        }

        .perfil-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            margin-top: 20px;
            background: linear-gradient(45deg, #f3f3f3, #e2e2e2);
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .perfil-footer h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4b2a9b;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .perfil-footer:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, #ffffff, #eaeaea);
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-around">
            <div class="perfil-container">
                <div class="perfil-header">
                    <div class="profile-pic">
                        <div class="background"></div>
                        <?php if ($usuario['usu_foto'] == null) : ?>
                            <img src="img/perfil-padrao.png" alt="Foto de perfil de <?= htmlspecialchars($usuario['usu_nome']) ?>" class="perfil-pic">
                        <?php else : ?>
                            <img src="<?= $usuario['usu_foto'] ?>" alt="Foto de perfil de <?= htmlspecialchars($usuario['usu_nome']) ?>" class="perfil-pic">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="perfil-info mt-3">
                    <h1><?= htmlspecialchars($usuario['usu_nome']) ?></h1>
                    <p><i class="bi bi-envelope"></i> <?= htmlspecialchars($usuario['usu_email']) ?></p>
                </div>
                <div class="perfil-footer">
                    <h3>Criou a conta em <?= date('d/m/Y', strtotime($usuario['created_at'])) ?></h3>
                </div>
            </div>
            <div class="perfil-container">
                <h4 class="text-center fw-bold">Atividades Recentes</h4>
                <?php if (count($atividades) > 0) : ?>
                    <ul class="activity-list">
                        <?php foreach ($atividades as $atividade) : ?>
                            <li>
                                <span class="activity-text">Comentou em "<a href="detalhesnoticias.php?id=<?= $atividade['not_codigo'] ?>" style="color:black;"><strong><?= htmlspecialchars($atividade['not_titulo']) ?></strong></a>"</span>
                                <a href="detalhesnoticias.php?id=<?= $atividade['not_codigo'] ?>" class="btn-access">Acessar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                <?php else : ?>
                    <p class="without-activities">
                        Não possui nenhuma atividade recente!
                    </p>
                    <a href="index.php" class="btn-access text-center mt-5">Volte a ver notícias!</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>