<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> dados</title>
</head>
<body>
    <?php
    $nome = $_GET["nome"];
    $peso = $_GET["peso"];
    $altura = $_GET["altura"];
    $conta = $peso / ($altura*$altura);

    echo "Seu nome é: " .$nome. "<br> sua altura é: " . $altura . "<br> seu peso é: " . $peso . "<br> seu IMC é: " .round($conta, 2); 
    ?>
</body>
</html>