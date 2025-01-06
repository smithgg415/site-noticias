<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['logado099']) || $_SESSION['logado099'] === false) {
    header("Location: login.php");
    exit();
}

require "bd/conexao.php";
$conexao = conexao::getInstance();

// Supondo que a informação do usuário esteja na tabela 'usuarios'
$sqlUsuario = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
$stmt = $conexao->prepare($sqlUsuario);
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_OBJ);

// Buscar as atividades recentes (comentários feitos pelo usuário, nos últimos 3 horas)
$sqlAtividades = 'SELECT c.com_conteudo, n.not_titulo, c.com_criadoem, c.not_codigo
                  FROM comentarios c
                  INNER JOIN noticias n ON c.not_codigo = n.not_codigo
                  WHERE c.usu_codigo = :usuario_id
                  AND c.com_criadoem > NOW() - INTERVAL 3 HOUR
                  ORDER BY c.com_criadoem DESC';
$stmtAtividades = $conexao->prepare($sqlAtividades);
$stmtAtividades->bindParam(':usuario_id', $_SESSION['id']);
$stmtAtividades->execute();
$atividades = $stmtAtividades->fetchAll(PDO::FETCH_OBJ);

// Função auxiliar para mostrar o tempo passado (ex: "2 horas atrás")
function time_ago($datetime)
{
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;

    // Definir os períodos de tempo
    $periods = [
        'ano' => 31536000, // 60*60*24*365
        'mês' => 2592000, // 60*60*24*30
        'semana' => 604800, // 60*60*24*7
        'dia' => 86400, // 60*60*24
        'hora' => 3600, // 60*60
        'minuto' => 60, // 60
    ];

    // Loop para calcular o tempo adequado
    foreach ($periods as $unit => $value) {
        $unit_time = floor($difference / $value);
        if ($unit_time > 0) {
            // Pluraliza se necessário
            $unit_display = $unit_time > 1 ? $unit . "s" : $unit;
            return "$unit_time $unit_display atrás";
        }
    }

    return "Agora"; // Se a diferença for menor que 1 minuto
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($usuario->usu_nome) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Estilo geral do perfil */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #343a40;
            margin-top: 70px;
        }

        .container {
            max-width: 1200px;
        }

        .profile-header {
    background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-header h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .profile-header p {
            font-size: 1.1rem;
            margin-top: 10px;
        }

        .profile-header img {
            border-radius: 50%;
            border: 4px solid white;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .profile-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .profile-actions .btn {
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .profile-actions .btn-primary {
    background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1, #fff);

            color: white;
        }

        .profile-actions .btn-primary:hover {
            background-color: #0056b3;
        }

        .profile-actions .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .profile-actions .btn-secondary:hover {
            background-color: #bdbdbd;
        }

        .profile-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-section h3 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #343a40;
            color: white;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Cabeçalho do perfil -->
        <div class="profile-header">
            <h2><?= htmlspecialchars($usuario->usu_nome) ?></h2>
            <p>@<?= htmlspecialchars($usuario->usu_email) ?></p>
        </div>

        <!-- Ações do perfil -->
        <!-- <div class="profile-actions">
            <a href="editarperfil.php" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar Perfil
            </a>
            <a href="editarusuario.php?id=<? $usuario['usu_codigo'] ?>" class="btn btn-secondary">
                <i class="bi bi-lock"></i> Alterar Senha
            </a>
        </div> -->

        <!-- Seção de informações do usuário -->
        <div class="profile-section">
            <h3>Informações Pessoais</h3>
            <form action="atualizarperfil.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario->usu_email) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" class="form-control" name="nome" value="<?= htmlspecialchars($usuario->usu_nome) ?>" readonly>
                </div>
                
                <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
            </form>
        </div>

        <!-- Atividades Recentes -->
        <div class="profile-section">
            <h3>Atividades Recentes</h3>
            <?php if (count($atividades) > 0) : ?>
                <ul>
                    <?php foreach ($atividades as $atividade) : ?>
                        <li>
                            <strong>Comentou</strong> na notícia:
                            "<a href="detalhesnoticias.php?id=<?= $atividade->not_codigo ?>"><?= htmlspecialchars($atividade->not_titulo) ?></a>"
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Você ainda não fez nenhuma atividade recente.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> InfoNews. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>