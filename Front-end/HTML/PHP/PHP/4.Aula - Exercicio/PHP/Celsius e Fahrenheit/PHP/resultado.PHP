<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculo Temperatura</title>
</head>

<body>
    <section>
    <?php

        print_r($_GET["nome"]);
        print_r($_GET["valor"]);
        print_r($_GET["temp"]);
        print_r($_GET["temp2"]);

        
        $temperatura = $_GET["valor"];
        $texto = $_GET["temp"];
        $contaC_F = ($temperatura * 9 / 5) + 32;
        $contaF_C = ($temperatura - 32) * 5 / 9;

        echo "o calculo de " . $texto . "é igual a: " . "onselect=calcular"; 
        ?>
    </section>
</body>

</html>