<?php
require_once "valida_acesso.php";

    //Organizando os dados, retirando | dos possiveis valores
    $id = str_replace('|', '-', $_SESSION['id']);
    $perfil = str_replace('|', '-', $_SESSION['perfil']);
    $nome = str_replace('|', '-', $_SESSION['nome']);
    $titulo = str_replace('|', '-', $_POST['titulo']);
    $categoria = str_replace('|', '-', $_POST['categoria']);
    $descricao = str_replace('|', '-', $_POST['descricao']);
    
    //Concatenando os valores de cada parâmetro, separado por |
    $dados = $id . '|' . $perfil . '|' . $nome . '|' . $titulo . '|' . $categoria . '|' . $descricao . PHP_EOL;

    var_dump($dados);

    //Abrindo o arquivo e armazenando em uma variável
    $arquivo = fopen('registros.hd', 'a');

    //escrevendo no arquivo
    fwrite($arquivo, $dados);

    //Fechando arquivo
    fclose($arquivo);

    header('location: abrir_chamado.php?cadastro=efetuado')
?>