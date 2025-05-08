<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php 

    $nome = "Meu nome é Fernando";
    $idade = "Minha idade é 20 anos";
    $salario = "Meu salário é desemprego";
    
    $fichacompleta = $nome . " - " . $idade . " - " . $salario . "<br>";
    echo $fichacompleta;
    /* -------------------------------------------------------*/

    $nome2 = "Meu nome é Fernando";
    $idade2 = "Minha idade é 20 anos";
    $salario2 = "Meu salário é desemprego";

    $fichacompleta2 = "$nome2 - ";
    $fichacompleta2 .= "$idade2 - ";
    $fichacompleta2 .= "$salario2 <br>";
    echo $fichacompleta2;
    /* -------------------------------------------------------*/

    $nome3 = "Meu nome é Fernando";
    $idade3 = "Minha idade é 20 anos";
    $salario3 = "Meu salário é desemprego";

    echo "meu nome: {$nome3} - minha idade: {$idade3} - meu salario: {$salario3}";
    
    ?>
</body>
</html>