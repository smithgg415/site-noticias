<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Sistema de Cadastro de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
    <?php
    session_start();
    require 'bd/conexao.php';
    $conexao = conexao::getInstance();
    $acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
    $usu_codigo = (isset($_POST['id'])) ? $_POST['id'] : '';
    $usu_nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $usu_email = (isset($_POST['email'])) ? $_POST['email'] : '';
    $usu_senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
    $usu_nivel = (isset($_POST['nivel'])) ? $_POST['nivel'] : '';

    $mensagem = '';
    if ($acao != 'excluir'):
        if ($usu_nome == '' || strlen($usu_nome) < 3):
            $mensagem .= '<li>Favor preencher o Nome com pelo menos 3 caracteres.</li>';
        endif;
        if (!filter_var($usu_email, FILTER_VALIDATE_EMAIL)):
            $mensagem .= '<li>Favor fornecer um email válido.</li>';
        endif;
        if ($usu_senha == '' || strlen($usu_senha) < 6):
            $mensagem .= '<li>Favor preencher a Senha com pelo menos 6 caracteres.</li>';
        endif;
        if ($mensagem != ''):
            $mensagem = '<ul>' . $mensagem . '</ul>';
            echo "<div class='alert alert-danger' role='alert'>" . $mensagem . "</div>";
            exit;
        endif;
    endif;

    if ($acao == 'incluir') {
        $sql = 'SELECT usu_codigo FROM usuarios WHERE usu_email = :email';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':email', $usu_email);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            header("Location: register.php?mensagem=" . urlencode("Email já está em uso. Tente outro."));
            exit;
        }

        $sql = 'INSERT INTO usuarios (usu_nome, usu_email, usu_senha, usu_nivel) VALUES (:usu_nome, :usu_email, :usu_senha, :usu_nivel)';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_nome', $usu_nome);
        $stm->bindValue(':usu_email', $usu_email);
        $stm->bindValue(':usu_senha', password_hash($usu_senha, PASSWORD_DEFAULT));
        $stm->bindValue(':usu_nivel', $usu_nivel);
        $retorno = $stm->execute();

        if ($retorno) {
            header("Location: register.php?mensagem=" . urlencode("Usuário registrado com sucesso!"));
        } else {
            header("Location: register.php?mensagem=" . urlencode("Erro ao registrar usuário. Tente novamente."));
        }
        exit;
    }


    if ($acao == 'editar'):

        $sql = 'UPDATE usuarios SET usu_nome=:usu_nome, usu_email=:usu_email, usu_senha=:usu_senha, usu_nivel=:usu_nivel WHERE usu_codigo=:usu_codigo';

        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_nome', $usu_nome);
        $stm->bindValue(':usu_email', $usu_email);

        if (!empty($usu_senha)) {
            $hashed_password = password_hash($usu_senha, PASSWORD_DEFAULT);
        } else {
            $query = $conexao->prepare('SELECT usu_senha FROM usuarios WHERE usu_codigo = :usu_codigo');
            $query->bindValue(':usu_codigo', $usu_codigo);
            $query->execute();
            $hashed_password = $query->fetchColumn();
        }

        $stm->bindValue(':usu_senha', $hashed_password);
        $stm->bindValue(':usu_nivel', $usu_nivel);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();

        if ($retorno):
            echo "<meta http-equiv=refresh content='0;URL=indexnoticia.php'>";
        else:
            echo "<div class='alert alert-danger' role='alert'>Erro ao editar usuário!</div>";
            echo "<meta http-equiv=refresh content='2;URL=indexnoticia.php'>";
        endif;

    endif;
    if ($acao == 'editar-conta'):

        $sql = 'UPDATE usuarios SET usu_nome=:usu_nome, usu_email=:usu_email, usu_senha=:usu_senha, usu_nivel=:usu_nivel WHERE usu_codigo=:usu_codigo';

        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_nome', $usu_nome);
        $stm->bindValue(':usu_email', $usu_email);

        if (!empty($usu_senha)) {
            $hashed_password = password_hash($usu_senha, PASSWORD_DEFAULT);
        } else {
            $query = $conexao->prepare('SELECT usu_senha FROM usuarios WHERE usu_codigo = :usu_codigo');
            $query->bindValue(':usu_codigo', $usu_codigo);
            $query->execute();
            $hashed_password = $query->fetchColumn();
        }

        $stm->bindValue(':usu_senha', $hashed_password);
        $stm->bindValue(':usu_nivel', $usu_nivel);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();

        if ($retorno):
            $_SESSION['msg'] = 'Alteração realizada com sucesso!';
            header("Location: perfil.php"); 
            exit;
        else:
            $_SESSION['msg'] = 'Erro ao editar usuário!';
            header("Location: perfil.php");
            exit;
        endif;

    endif;

    if ($acao == 'excluir-conta'):

        session_start();

        session_unset();
        session_destroy(); 

        $sql = 'DELETE FROM usuarios WHERE usu_codigo=:usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();

        if ($retorno):
            echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
        else:
            echo "<div class='alert alert-danger' role='alert'>Erro ao excluir conta!</div>";
            echo "<meta http-equiFv='refresh' content='2;URL=index.php'>";
        endif;

    endif;


    if ($acao == 'excluir'):

        $sql = 'DELETE FROM usuarios WHERE usu_codigo=:usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();

        if ($retorno):
            echo "<meta http-equiv=refresh content='0;URL=indexnoticia.php'>";
        else:
            echo "<div class='alert alert-danger' role='alert'>Erro ao excluir usuário!</div>";
            echo "<meta http-equiv=refresh content='2;URL=indexnoticia.php'>";
        endif;

    endif;
    ?>
</body>

</html>