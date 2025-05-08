<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> IMC </title>
</head>
<body>
    <?php
    $altura = "1.73";
    $peso = "60";
    $conta = $peso / ($altura*$altura);

    echo "A sua altura é: " . $altura . "<br> e seu peso é: " . $peso . "<br> seu imc é igual a: " . round($conta, 2);
    ?>
</body>
</html>