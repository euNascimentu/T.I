<?php 

    require "classes.php";

    $modelo = $_GET['modelo'];
    $cor = $_GET['cor'];

    $carro = new $modelo();

    $carro->__SET('modelo', $modelo);
    $carro->__SET('cor', $cor);
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
    </style>
    <link rel="stylesheet" href="style.CSS">
    <title> Carros </title>
</head>

<body>
    <header>
        <h1 class="cabTexto"> Asfalto Urbano </h1>
    </header>
    <section>
        <?php echo $carros->__get('imagens'); ?>
        <img class="imagem" src="source/<?php echo $carros->__get('imagens'); ?>.jpg" alt="carro select">
    </section>
</body>

</html>