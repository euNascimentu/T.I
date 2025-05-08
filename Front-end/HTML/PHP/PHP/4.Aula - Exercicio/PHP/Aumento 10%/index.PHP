<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Exercicio PHP </title>
</head>
<body>
    <?php
    $preco = 100;  
    $novoPreco = $preco * 1.10;  

    echo "Valor Bruto: " .$preco ."<br>";
    echo "Novo preço: R$ " . $novoPreco

    /*number_format($novoPreco, 2, ',', '.');*/
    ?>
</body>
</html>