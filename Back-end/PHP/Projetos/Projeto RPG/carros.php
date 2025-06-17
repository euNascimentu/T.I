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
    <section id="sectionResult">
        <div class="image2">
            <img class="imagem2" src="source/<?php echo $carro->__get('imagens'); ?>.jpg" alt="carro select">
        </div>
        <article>
            <div class="escolhaUser">
                <h3 class="">Modelo: <?php echo $carro->__get('modelo'); ?></h3>
                <h3 class="">Cor: <?php echo $carro->__get('cor'); ?> </h3>
                <h3 class="">Marca: <?php echo $carro->__get('marca'); ?></h3>
                <h3 class="">Categoria: <?php echo $carro->__get('categoria'); ?></h3>
                <h3 class="">Velocidade: <?php echo $carro->__get('velocidade'); ?></h3>
            </div>
            <h1>
                <?php
                if ($modelo == 'Compass')
                    echo "Versatilidade e Conforto sem Limites";
                elseif ($modelo =='GT3RS')
                    echo "Pura Adrenalina com Sofisticação";
                elseif ($modelo =='PassatHighLine')
                    echo "Elegância Alemã em Cada Detalhe";
                elseif ($modelo =='GLA200')
                    echo "Sofisticação Compacta";
                elseif ($modelo =='R8')
                    echo "O Supercarro Acessível";
                ?>
            </h1>
        </article>
        <footer>
            <a id="botaoVoltar" href="index.HTML">Voltar</a>
        </footer>
    </section>
</body>

</html>