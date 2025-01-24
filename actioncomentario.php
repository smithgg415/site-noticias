<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ações de Comentários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="css/custom.css">
</head>

<body>
    <?php
    session_start();
    require 'bd/conexao.php';

    $conexao = conexao::getInstance();

    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $conteudo = trim(filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_STRING));
    $not_codigo = filter_input(INPUT_POST, 'not_codigo', FILTER_VALIDATE_INT);

    $mensagem = '';

    if ($acao != 'excluir') {
        if (empty($conteudo) || strlen($conteudo) < 3) {
            $mensagem .= '<li>O conteúdo do comentário deve ter pelo menos 3 caracteres.</li>';
        }
        if ($mensagem != '') {
            $mensagem = '<ul>' . $mensagem . '</ul>';
            echo "<div class='alert alert-danger' role='alert'>" . $mensagem . "</div>";
            exit;
        }
    }

    if ($acao == 'incluir') {
        if (!isset($_SESSION['logado099']) || $_SESSION['logado099'] !== true) {
            echo "<div class='alert alert-danger' role='alert'>Você precisa estar logado para comentar!</div>";
            exit;
        }

        $usu_codigo = $_SESSION['id'];

        $sql = 'INSERT INTO comentarios (usu_codigo, not_codigo, com_conteudo) VALUES (:usu_codigo, :not_codigo, :conteudo)';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':usu_codigo', $usu_codigo);
        $stm->bindValue(':not_codigo', $not_codigo);
        $stm->bindValue(':conteudo', $conteudo);
        $retorno = $stm->execute();

        if ($retorno) {
            echo "<meta http-equiv='refresh' content='0;URL=detalhesnoticias.php?id=$not_codigo'>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao adicionar o comentário!</div>";
            echo "<meta http-equiv='refresh' content='2;URL=detalhesnoticias.php?id=$not_codigo'>";
        }
    }

    if ($acao == 'editar') {
        $sql = 'SELECT not_codigo FROM comentarios WHERE com_codigo = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', $id);
        $stm->execute();
        $resultado = $stm->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $not_codigo = $resultado['not_codigo'];
            $sql = 'UPDATE comentarios SET com_conteudo = :conteudo WHERE com_codigo = :id';
            $stm = $conexao->prepare($sql);
            $stm->bindValue(':conteudo', $conteudo);
            $stm->bindValue(':id', $id);
            $retorno = $stm->execute();

            if ($retorno) {
                echo "<meta http-equiv='refresh' content='0;URL=detalhesnoticias.php?id=$not_codigo'>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Erro ao editar o comentário!</div>";
                echo "<meta http-equiv='refresh' content='2;URL=editarcomentario.php?id=$id'>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro: Comentário não encontrado!</div>";
        }
    }


    if ($acao == 'excluir') {
        $sql = 'DELETE FROM comentarios WHERE com_codigo = :id';
        $stm = $conexao->prepare($sql);
        $stm->bindValue(':id', $id);
        $retorno = $stm->execute();

        if ($retorno) {
            echo "<meta http-equiv='refresh' content='0;URL=index.php'>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erro ao excluir o comentário!</div>";
            echo "<meta http-equiv='refresh' content='2;URL=detalhesnoticias.php?id=$com_codigo'>";
        }
    }
    ?>
</body>

</html>