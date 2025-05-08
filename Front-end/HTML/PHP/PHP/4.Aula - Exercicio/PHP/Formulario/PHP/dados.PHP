<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.CSS">
    <title> dados</title>
</head>
<body id="dados">
    <section class="grafico">
    <?php
    $nome = $_GET["nome"];
    $peso = $_GET["peso"];
    $altura = $_GET["altura"];
    $conta = $peso / ($altura*$altura);
    $conta = round($conta,2);

    echo "<p>Seu nome é: {$nome} <br> sua altura é: {$altura} <br> seu peso é: {$peso} <br> seu IMC é: {$conta}</p>";
    ?>
    </section>
</body>
</html>