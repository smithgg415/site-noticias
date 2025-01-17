<?php
session_start();

if (!isset($_SESSION['logado099']) || $_SESSION['logado099'] === false) {
    header("Location: login.php");
    exit();
}

require "bd/conexao.php";
$conexao = conexao::getInstance();

$sqlUsuario = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
$stmt = $conexao->prepare($sqlUsuario);
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_OBJ);

$sqlAtividades = 'SELECT c.com_conteudo, n.not_titulo, n.not_codigo, c.com_criadoem, c.not_codigo
                  FROM comentarios c
                  INNER JOIN noticias n ON c.not_codigo = n.not_codigo
                  WHERE c.usu_codigo = :usuario_id
                  AND c.com_criadoem > NOW() - INTERVAL 3 HOUR
                  ORDER BY c.com_criadoem DESC';
$stmtAtividades = $conexao->prepare($sqlAtividades);
$stmtAtividades->bindParam(':usuario_id', $_SESSION['id']);
$stmtAtividades->execute();
$atividades = $stmtAtividades->fetchAll(PDO::FETCH_OBJ);

function time_ago($datetime)
{
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    $periods = ['ano' => 31536000, 'mês' => 2592000, 'semana' => 604800, 'dia' => 86400, 'hora' => 3600, 'minuto' => 60];
    foreach ($periods as $unit => $value) {
        $unit_time = floor($difference / $value);
        if ($unit_time > 0) {
            return "$unit_time " . ($unit_time > 1 ? $unit . "s" : $unit) . " atrás";
        }
    }
    return "Agora";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | <?= htmlspecialchars($usuario->usu_nome) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
        }

        .card-custom {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            height: 500px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-pic-container {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-pic-container img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            border: 5px solid #007bff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .camera-icon {
            position: absolute;
            bottom: 10px;
            right: 15%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 12px;
            border-radius: 50%;
            height: 45px;
            width: 45px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .camera-icon:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .btn-primary {
            background: linear-gradient(135deg, #5c3c9b, #9c57e6);
            border: none;
            padding: 12px 20px;
            font-weight: bold;
            border-radius: 50px;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background: #9c57e6;
            transform: scale(1.05);
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
            transition: 0.3s ease;
        }

        .activity-list li:hover {
            background: #eaeaea;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .activity-list .activity-text {
            font-size: 16px;
            font-weight: 500;
            color: #333;
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

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .camera-icon {
                right: 10%;
            }
        }

        footer {
            background-color: #4b2a9b;
            color: white;
            padding: 40px 20px;
            margin-top: 40px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        footer .footer-links div {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }

        footer .footer-links h5 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            text-decoration: underline;
            color: #a02ae1;
        }

        footer .footer-social-icons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        footer .footer-social-icons a {
            color: white;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        footer .footer-social-icons a:hover {
            color: #a02ae1;
        }

        .footer-bottom {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 20px;
            color: #ddd;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            footer .footer-links {
                flex-direction: column;
                align-items: center;
            }

            footer .footer-links div {
                text-align: center;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            footer .footer-links h5 {
                font-size: 1rem;
            }

            footer .footer-links a {
                font-size: 0.9rem;
            }

            footer .footer-social-icons a {
                font-size: 1.2rem;
            }

            .footer-bottom {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow" style="
            background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="bi bi-newspaper"></i> INFONEWS
            </a>
            <a href="index.php" class="btn btn-primary">Voltar</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-custom text-center">
                    <div class="profile-pic-container">
                        <img id="profile-pic" src="perfil-padrao.png" alt="Foto de perfil">
                        <i class="bi bi-camera-fill camera-icon" data-bs-toggle="modal" data-bs-target="#uploadModal"></i>
                    </div>

                    <h4 class="fw-bold" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"><?= htmlspecialchars($usuario->usu_nome) ?></h4>
                    <p class="text-muted" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><?= htmlspecialchars($usuario->usu_email) ?></p>
                    <a href="editarusuario.php?id=<?= $usuario->usu_codigo ?>"><button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Atualizar perfil</button></a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h4 class="text-center fw-bold" style="font-family:Verdana, Geneva, Tahoma, sans-serif">Atividades Recentes</h4>
                    <?php if (count($atividades) > 0) : ?>
                        <ul class="activity-list">
                            <?php foreach ($atividades as $atividade) : ?>
                                <li>
                                    <span class="activity-text">Comentou em "<a href="detalhesnoticias.php?id=<?= $atividade->not_codigo ?>" style="color:black;"><strong><?= htmlspecialchars($atividade->not_titulo) ?></strong></a>"</span>
                                    <a href="detalhesnoticias.php?id=<?= $atividade->not_codigo ?>" class="btn-access">Acessar</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-center text-muted">Nenhuma atividade recente.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Atualizar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" class="form-control" id="fileInput" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="deleteBtn">Excluir foto</button>
                    <button type="button" class="btn btn-primary" id="uploadBtn">Salvar Foto</button>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container footer-links">
            <div>
                <h5>Sobre nós</h5>
                <p>Somos uma plataforma de notícias dedicada a trazer o melhor conteúdo de forma clara e objetiva.</p>
            </div>
            <div>
                <h5>Contato</h5>
                <p>Email: contato@infonews.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
            </div>
            <div>
                <h5>Links úteis</h5>
                <a href="#">Política de Privacidade</a>
                <a href="#">Termos de Serviço</a>
                <a href="#">FAQ</a>
            </div>
        </div>

        <div class="footer-social-icons">
            <a href="https://www.facebook.com" target="_blank" class="bi bi-facebook"></a>
            <a href="https://twitter.com" target="_blank" class="bi bi-twitter"></a>
            <a href="https://www.instagram.com" target="_blank" class="bi bi-instagram"></a>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 INFONEWS. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('uploadBtn').addEventListener('click', function() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    localStorage.setItem('profilePic', reader.result);
                    document.getElementById('profile-pic').src = reader.result;
                    const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                    modal.hide();
                };
                reader.readAsDataURL(file);
            } else {
                alert("Por favor, selecione uma imagem.");
            }
        });
        document.getElementById('deleteBtn').addEventListener('click', function() {
            localStorage.removeItem('profilePic');
            document.getElementById('profile-pic').src = 'img/perfil-padrao.png';
        });
        window.onload = function() {
    const savedPic = localStorage.getItem('profilePic');
    const profilePic = document.getElementById('profile-pic');
    const deleteBtn = document.getElementById('deleteBtn');

    if (savedPic) {
        profilePic.src = savedPic;
    } else {
        profilePic.src = 'img/perfil-padrao.png'; 
    }

    checkDeleteBtnVisibility(profilePic.src, deleteBtn);
};

function checkDeleteBtnVisibility(picSrc, deleteBtn) {
    if (picSrc.includes('perfil-padrao.png')) {
        deleteBtn.style.display = 'none';
    } else {
        deleteBtn.style.display = 'inline-block';
    }
}

document.getElementById('deleteBtn').addEventListener('click', function() {
    localStorage.removeItem('profilePic');
    const profilePic = document.getElementById('profile-pic');
    const deleteBtn = document.getElementById('deleteBtn');
    
    profilePic.src = 'img/perfil-padrao.png';

    checkDeleteBtnVisibility(profilePic.src, deleteBtn);
});

    </script>
</body>

</html>
