<?php
session_start();
require 'bd/conexao.php';
$conexao = conexao::getInstance();

$acao = $_POST['acao'] ?? '';
$usu_codigo = $_POST['id'] ?? '';
$usu_foto = $_POST['usu_foto'] ?? '';
$foto_de_fundo = $_POST['foto_de_fundo'] ?? '';

$mensagem = '';
if (!in_array($acao, ['excluir-foto', 'editar-foto', 'excluir-foto-de-fundo', 'editar-foto-de-fundo'])) {
    $mensagem .= '<li>Ação inválida.</li>';
}

if ($acao !== 'excluir-foto' && $acao !== 'excluir-foto-de-fundo') {
    if ($usu_foto === '' && $foto_de_fundo === '') {
        $mensagem .= '<li>Favor fornecer um link válido para a foto ou foto de fundo.</li>';
    }
    if ($usu_foto && !filter_var($usu_foto, FILTER_VALIDATE_URL)) {
        $mensagem .= '<li>Favor fornecer um link válido para a foto.</li>';
    }
    if ($foto_de_fundo && !filter_var($foto_de_fundo, FILTER_VALIDATE_URL)) {
        $mensagem .= '<li>Favor fornecer um link válido para a foto de fundo.</li>';
    }
}

if ($mensagem !== '') {
    echo "<div class='alert alert-danger' role='alert'><ul>$mensagem</ul></div>";
    exit;
}

switch ($acao) {
    case 'editar-foto':
        $sql = 'UPDATE usuarios SET usu_foto = :usu_foto WHERE usu_codigo = :usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_foto', $usu_foto);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();
        $_SESSION['msg'] = $retorno ? 'Foto alterada com sucesso!' : 'Erro ao alterar foto!';
        break;

    case 'excluir-foto':
        $sql = 'UPDATE usuarios SET usu_foto = NULL WHERE usu_codigo = :usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();
        $_SESSION['msg'] = $retorno ? 'Foto excluída com sucesso!' : 'Erro ao excluir foto!';
        break;

    case 'editar-foto-de-fundo':
        $sql = 'UPDATE usuarios SET usu_foto_de_fundo = :foto_de_fundo WHERE usu_codigo = :usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':foto_de_fundo', $foto_de_fundo);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();
        $_SESSION['msg'] = $retorno ? 'Foto de fundo alterada com sucesso!' : 'Erro ao alterar foto de fundo!';
        break;

    case 'excluir-foto-de-fundo':
        $sql = 'UPDATE usuarios SET usu_foto_de_fundo = NULL WHERE usu_codigo = :usu_codigo';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $retorno = $stm->execute();
        $_SESSION['msg'] = $retorno ? 'Foto de fundo excluída com sucesso!' : 'Erro ao excluir foto de fundo!';
        break;

    default:
        $_SESSION['msg'] = 'Ação inválida!';
}

header("Location: perfil.php");
exit;
