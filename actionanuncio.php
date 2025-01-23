<?php
    session_start();

    if (!isset($_SESSION["logado099"])) {
        header("Location: index.php");
        exit;
    }

    require 'bd/conexao.php';
    $conexao = conexao::getInstance();

    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $imagem = isset($_POST['imagem']) ? $_POST['imagem'] : '';
    $linkacesso = isset($_POST['linkacesso']) ? $_POST['linkacesso'] : '';
    $mensagem = '';

    // Validação para não excluir
    if ($acao != 'excluir') {
        if ($imagem == '' || strlen($imagem) < 3) {
            $mensagem .= '<li>Favor preencher o campo Imagem (mínimo 3 caracteres).</li>';
        }
        if ($linkacesso == '' || strlen($linkacesso) < 10) {
            $mensagem .= '<li>Favor preencher o campo Link de Acesso (mínimo 10 caracteres).</li>';
        }
        if ($mensagem != '') {
            $mensagem = '<ul>' . $mensagem . '</ul>';
            echo "<div class='alert alert-danger' role='alert'>" . $mensagem . "</div>";
            exit;
        }
    }

    if ($acao == 'incluir') {
        $sql = 'INSERT INTO anuncios (anu_nome, anu_imagem, anu_linkacesso) VALUES (:nome, :imagem, :linkacesso)';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':nome', $nome);
        $stm->bindValue(':imagem', $imagem);
        $stm->bindValue(':linkacesso', $linkacesso);
        $retorno = $stm->execute();

        if ($retorno) {
            $_SESSION['mensagem'] = 'Anúncio adicionado com sucesso!';
            header('Location: indexnoticia.php');
            exit;
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao inserir anúncio!</div>";
            header('Location: addanuncio.php');
            exit;
        }
    }

    if ($acao == 'editar') {
        $sql = 'UPDATE anuncios SET anu_nome=:nome, anu_imagem=:imagem, anu_linkacesso=:linkacesso WHERE anu_codigo=:id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':nome', $nome);
        $stm->bindValue(':imagem', $imagem);
        $stm->bindValue(':linkacesso', $linkacesso);
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        if ($retorno) {
            $_SESSION['mensagem'] = '';
            header('Location: indexnoticia.php');
            exit;
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao editar anúncio!</div>";
            header('Location: editaranuncio.php?id=' . $id);
            exit;
        }
    }

    if ($acao == 'excluir') {
        $sql = 'DELETE FROM anuncios WHERE anu_codigo=:id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        if ($retorno) {
            $_SESSION['mensagem'] = 'Anúncio excluído com sucesso!';
            header('Location: indexnoticia.php');
            exit;
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao excluir anúncio!</div>";
            header('Location: indexnoticia.php');
            exit;
        }
    }
    ?>
