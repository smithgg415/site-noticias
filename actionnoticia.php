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
$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
$conteudo = isset($_POST['conteudo']) ? $_POST['conteudo'] : '';
$imagem = isset($_POST['imagem']) ? $_POST['imagem'] : '';
$categoria = isset($_POST['categoria']) && $_POST['categoria'] !== '' ? $_POST['categoria'] : NULL;
$mensagem = '';

if ($acao != 'excluir') {
    if ($titulo == '' || strlen($titulo) < 3) {
        $mensagem .= '<li>Favor preencher o Título (mínimo 3 caracteres).</li>';
    }
    if ($conteudo == '' || strlen($conteudo) < 10) {
        $mensagem .= '<li>Favor preencher o Conteúdo (mínimo 10 caracteres).</li>';
    }
    if ($mensagem != '') {
        $mensagem = '<ul>' . $mensagem . '</ul>';
        echo "<div class='alert alert-danger' role='alert'>" . $mensagem . "</div>";
        exit;
    }
}

if ($acao == 'incluir') {
    $sql = 'INSERT INTO noticias (not_titulo, not_conteudo, not_imagem, not_autor_codigo, not_categoria) 
            VALUES (:titulo, :conteudo, :imagem, :autor_codigo, :categoria)';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':titulo', $titulo);
    $stm->bindValue(':conteudo', $conteudo);
    $stm->bindValue(':imagem', $imagem);
    $stm->bindValue(':autor_codigo', $_SESSION['id']);

    if ($categoria === NULL) {
        $stm->bindValue(':categoria', NULL, PDO::PARAM_NULL);
    } else {
        $stm->bindValue(':categoria', $categoria, PDO::PARAM_STR);
    }

    $retorno = $stm->execute();

    if ($retorno) {
        $_SESSION['mensagem'] = 'Notícia adicionada com sucesso!';
        header('Location: indexnoticia.php');
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erro ao inserir notícia!</div>";
        header('Location: addnoticia.php');
        exit;
    }
}

if ($acao == 'editar') {
    $sql = 'UPDATE noticias SET not_titulo=:titulo, not_conteudo=:conteudo, not_imagem=:imagem, not_categoria=:categoria WHERE not_codigo=:id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':titulo', $titulo);
    $stm->bindValue(':conteudo', $conteudo);
    $stm->bindValue(':imagem', $imagem);

    if ($categoria === NULL) {
        $stm->bindValue(':categoria', NULL, PDO::PARAM_NULL);
    } else {
        $stm->bindValue(':categoria', $categoria, PDO::PARAM_STR);
    }

    $stm->bindValue(':id', $id);
    $retorno = $stm->execute();

    if ($retorno) {
        $_SESSION['mensagem'] = 'Notícia editada com sucesso!';
        header('Location: indexnoticia.php');
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erro ao editar notícia!</div>";
        header('Location: editarnoticia.php?id=' . $id);
        exit;
    }
}

if ($acao == 'excluir') {
    $sql = 'DELETE FROM noticias WHERE not_codigo=:id';
    $stm = $conexao->prepare($sql);
    $stm->bindValue(':id', $id);
    $retorno = $stm->execute();

    if ($retorno) {
        $_SESSION['mensagem'] = 'Notícia excluída com sucesso!';
        header('Location: indexnoticia.php');
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erro ao excluir notícia!</div>";
        header('Location: index.php');
        exit;
    }
}
