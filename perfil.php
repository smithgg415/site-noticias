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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | <?= htmlspecialchars($usuario->usu_nome) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow" style="background: linear-gradient(135deg, #4b2a9b, #6933d1, #a02ae1);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#"><i class="bi bi-newspaper me-2 text-white"></i>INFONEWS</a>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-gear me-2"></i>
                Configurações
            </button>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row section" id="profile-activities">
            <div class="col-md-6">
                <div class="card-custom text-center">
                        <div class="profile-pic-container">
                            <img id="profile-pic" src="perfil-padrao.png" alt="Foto de perfil">
                            <i class="bi bi-camera-fill camera-icon"></i>
                        </div>
                    <h4 class="fw-bold"><?= htmlspecialchars($usuario->usu_nome) ?></h4>
                    <p class="information mb-5"><?= htmlspecialchars($usuario->usu_email) ?>
                        <i class="bi bi-envelope-fill me-2"></i>
                    </p>
                    <p class="information mb-5"> Seu nível aqui no InfoNews é <?= htmlspecialchars($usuario->usu_nivel) ?>
                        <i class="bi bi-award-fill me-2"></i>
                    </p>
                    <a href="editarusuario.php?id=<?= $usuario->usu_codigo ?>"><button class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square me-2"></i>
                            Atualizar perfil</button></a>
                    <a href="logout.php">
                        <button class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Sair
                        </button>
                    </a>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h4 class="text-center fw-bold">Atividades Recentes</h4>
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
                        <p class="without-activities">
                            Não encontramos nenhuma atividade recente!
                        </p>
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-newspaper me-2"></i>
                            Veja as notícias e reaja!</a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['msg'])) : ?>
                        <div class='alert alert-success' role='alert'>
                            <?= $_SESSION['msg']; ?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                    <?php endif; ?>
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

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Configurações</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 sidebar">
                                <ul class="nav flex-column" id="tabs">
                                    <div id="inicio">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab-1" data-bs-toggle="pill" href="#screen1">Verifique seus dados</a>
                                        </li>
                                    </div>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-2" data-bs-toggle="pill" href="#screen2">Políticas e Diretrizes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-3" data-bs-toggle="pill" href="#screen3">Desenvolvido por:</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 tab-content">
                                <div id="screen1" class="tab-pane fade show active">
                                    <p class="title-alteracao">Quer <span>alterar</span> algo?</p>


                                    <form method="post" action="actionusuario.php">
                                        <?php
                                        $sql = 'SELECT * FROM usuarios WHERE usu_codigo = :id';
                                        $stmt = $conexao->prepare($sql);
                                        $stmt->bindParam(':id', $_SESSION['id']);
                                        $stmt->execute();
                                        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
                                        ?>
                                        <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                                        <input type="hidden" name="acao" value="editar-conta">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" class="form-control" name="nome" id="nome" value="<?= $usuario->usu_nome ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" value="<?= $usuario->usu_email ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="senha" class="form-label">Senha</label>
                                            <input type="password" id="senha" name="senha" class="form-control" value="<?= $usuario->usu_senha ?>">
                                            <small class="text-muted">Não se preocupe, sua senha está criptografada! Caso queira mudar, apenas apague e digite a nova.</small>
                                        </div>
                                        <?php if ($usuario->usu_nivel == 'admin') : ?>
                                            <input type="hidden" name="nivel" value="admin">
                                        <?php else : ?>
                                            <input type="hidden" name="nivel" value="usuario">
                                        <?php endif; ?>
                                        <div class="container-saveBtn">
                                            <button type="submit" class="btn btn-primary" id="saveBtn">Salvar</button>
                                        </div>
                                    </form>
                                    <form action="actionusuario.php" method="post" class="form-delete">
                                        <input type="hidden" name="id" value="<?= $usuario->usu_codigo ?>">
                                        <p class="delete-message">Deseja excluir sua conta?</p>
                                        <button type="button" class="btn btn-danger" onclick="confirmarDecisao();">Excluir conta</button>

                                        <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                                            <div class="modal-dialog custom-dialog">
                                                <div class="modal-content custom-content">
                                                    <div class="modal-header custom-header">
                                                        <h5 class="modal-title" id="confirmarModalLabel">Confirmar Exclusão de Conta</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body custom-body">
                                                        <div class="row">
                                                            <p class="messageNumbers">Por favor, digite os 8 números abaixo para confirmar a exclusão da conta:</p>
                                                            <p class="generated-numbers"></p>
                                                        </div>
                                                        <input type="text" id="usuario-numeros" placeholder="Digite os 8 números" class="form-control">
                                                    </div>
                                                    <div class="modal-footer custom-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        <button type="button" class="btn btn-danger" onclick="verificarNumeros();">Confirmar Exclusão</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </form>




                                </div>
                                <div id="screen2" class="tab-pane fade">
                                    <div class="policy-container">
                                        <h2>Políticas e Diretrizes do InfoNews</h2>
                                        <p>
                                            As Políticas e Diretrizes do InfoNews visam promover uma experiência segura, justa e transparente para todos os usuários. Ao utilizar o InfoNews, você concorda em seguir as seguintes regras e diretrizes:
                                        </p>

                                        <h3>1. Privacidade e Proteção de Dados</h3>
                                        <p>
                                            O InfoNews se compromete a proteger a privacidade de seus usuários. Coletamos e processamos dados pessoais de acordo com nossa <a href="#">Política de Privacidade</a>, que descreve como suas informações são coletadas, utilizadas e protegidas. Garantimos que seus dados serão utilizados apenas para personalizar a experiência no site e enviar notificações relevantes, e nunca serão compartilhados com terceiros sem seu consentimento.
                                        </p>

                                        <h3>2. Comportamento e Conduta</h3>
                                        <p>Os usuários devem interagir de forma respeitosa e ética em todas as áreas do InfoNews. É proibido:</p>
                                        <ul>
                                            <li>Publicar conteúdo discriminatório, ofensivo ou de ódio.</li>
                                            <li>Realizar comentários que incitem violência ou discriminação.</li>
                                            <li>Compartilhar informações falsas, enganosas ou prejudiciais.</li>
                                            <li>Usar a plataforma para promover atividades ilegais ou fraudulentas.</li>
                                            <li>Assediar ou atacar outros usuários, seja por meio de mensagens ou comentários.</li>
                                        </ul>
                                        <p>
                                            A plataforma se reserva o direito de remover qualquer conteúdo que viole essas diretrizes e de suspender ou bloquear usuários que desrespeitem essas regras.
                                        </p>

                                        <h3>3. Propriedade Intelectual</h3>
                                        <p>
                                            Todo o conteúdo disponibilizado no InfoNews, incluindo textos, imagens, vídeos e logotipos, é protegido por direitos autorais. O usuário não deve copiar, distribuir, modificar ou utilizar qualquer material do site sem a devida autorização, a menos que tenha sido explicitamente permitido. O InfoNews respeita os direitos autorais e espera que seus usuários façam o mesmo.
                                        </p>

                                        <h3>4. Uso de Conteúdo</h3>
                                        <p>
                                            O InfoNews permite que os usuários compartilhem e comentem notícias, artigos e outros conteúdos. Ao enviar conteúdo para a plataforma, o usuário concede ao InfoNews uma licença não exclusiva, transferível e sublicenciável para usar, exibir, modificar e distribuir esse conteúdo, sempre que necessário, dentro da plataforma e seus canais de comunicação.
                                        </p>

                                        <h3>5. Segurança da Conta</h3>
                                        <p>
                                            Cada usuário é responsável pela segurança de sua conta no InfoNews. Recomendamos que você utilize senhas fortes e únicas, além de ativar a verificação em duas etapas, caso disponível.
                                        </p>
                                        <a href="#inicio"><button type="button" class="btn btn-primary">Voltar ao início</button></a>

                                    </div>

                                </div>
                                <div id="screen3" class="tab-pane fade">
                                    <div class="programador">
                                        <h4 class="title-programmer">Programador por trás disso:</h4>
                                        <p class="text-programmer">Luís Felipe Giacomelli Rodrigues, estudante de programação, focado em Sistemas Web e Aplicativos híbridos. CEO da StartUp Giacomelli Dev's, uma empresa desenvolvedora de sistemas.</p>
                                        <img src="img/giacomellidevslogo.png" alt="Giacomelli Dev's" style="width: 200px; height: 200px; border-radius: 50%; margin-bottom: 20px; border: 2px solid #9c57e6;">
                                    </div>
                                    <div class="social-medias">
                                        <a href="https://github.com/smithgg415" target="_blank">GITHUB <i class="bi-github"></i> </a>
                                        <a href="https://www.linkedin.com/in/lu%C3%ADs-felipe-giacomelli-rodrigues-1449842a9/" target="_blank">LINKEDIN <i class="bi-linkedin"></i></a>
                                        <a href="https://www.instagram.com/lf.giacomelli/" target="_blank">INSTAGRAM <i class="bi-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
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
        const uploadBtn = document.getElementById('uploadBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', function() {
                const fileInput = document.getElementById('fileInput');
                if (!fileInput) return;

                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        localStorage.setItem('profilePic', reader.result);
                        updateProfilePicture(reader.result);

                        const modalElement = document.getElementById('uploadModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert("Por favor, selecione uma imagem.");
                }
            });
        }

        const deleteBtn = document.getElementById('deleteBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                localStorage.removeItem('profilePic');
                updateProfilePicture('img/perfil-padrao.png');
            });
        }

        function updateProfilePicture(src) {
            const profilePic = document.getElementById('profile-pic');
            const deleteBtn = document.getElementById('deleteBtn');

            if (profilePic) {
                profilePic.src = src;
                checkDeleteBtnVisibility(src, deleteBtn);
            }
        }

        function checkDeleteBtnVisibility(picSrc, deleteBtn) {
            if (!deleteBtn) return;
            deleteBtn.style.display = picSrc.includes('perfil-padrao.png') ? 'none' : 'inline-block';
        }

        window.onload = function() {
            const savedPic = localStorage.getItem('profilePic') || 'img/perfil-padrao.png';
            updateProfilePicture(savedPic);
        };
        const cameraIcon = document.querySelector('.camera-icon');
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));

        cameraIcon.addEventListener('click', function() {
            uploadModal.show();
        });

        function gerarNumerosAleatorios() {
            let numeros = [];
            for (let i = 0; i < 8; i++) {
                numeros.push(Math.floor(Math.random() * 10));
            }
            return numeros;
        }

        function confirmarDecisao() {
            const numerosSorteados = gerarNumerosAleatorios().join('');
            localStorage.setItem('numerosSorteados', numerosSorteados);

            const numerosContainer = document.querySelector('#confirmarModal .generated-numbers');
            if (numerosContainer) {
                numerosContainer.textContent = numerosSorteados.split('').join(' '); 
            }

            const modal = new bootstrap.Modal(document.getElementById('confirmarModal'));
            modal.show();
        }

        function verificarNumeros() {
            const numerosSorteados = localStorage.getItem('numerosSorteados');
            const numerosInseridos = document.getElementById('usuario-numeros').value;

            if (numerosSorteados === numerosInseridos) {
                alert("Conta excluída com sucesso!");
                document.querySelector('.form-delete').submit();
            } else {
                alert("Os números inseridos estão incorretos. Tente novamente.");
            }
        }

        document.body.classList.add('modal-open');
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop show';
        document.body.appendChild(backdrop);
        document.body.classList.remove('modal-open');
        document.querySelector('.modal-backdrop').remove();
    </script>
</body>

</html>