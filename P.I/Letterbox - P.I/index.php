<?php
session_start(); // Sempre iniciar a sessão antes de qualquer HTML
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GameCut, Home! </title>
    <link rel="stylesheet" href="style/index.css">
    <style>
        /*Press start 2p*/
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');
        /*Bungee*/
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bungee&family=Press+Start+2P&display=swap');
    </style>
</head>
<body>
        <header>
            <div id="fundologo">
                <img class="logo" src="source/logoRoxa-Remove.png" alt="">
            </div>
            <div id="fundoButtons">
                <a class="a" href="">.um</a>
                <a class="a" href="">.dois</a>
                <a class="a" href="">.tres</a>
                <div id="perfilLogin">
                    <?php if (isset($_SESSION['usuario'])): ?>
                <!-- Usuário logado -->
                <a href="perfil.php">perfil</a>
                    <?php else: ?>
                        <!-- Usuário NÃO logado -->
                        <a href="login.html">login</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <div id="Banner">
            <img class="fotoBan" src="source/banner.gif" alt="">
        </div>
        <section>
            <p class="tituloConteudo">Lançamentos</p>
            <article>
                <div id="Cards">
                    <div class="fundoFotoCard">
                        <img class="fotoCard" src="source/gpt.png" alt="">
                    </div>
                    <div class="textoCard">
                        estou aqui
                    </div>
                </div>

                <div id="Cards">
                    <div class="fundoFotoCard">
                        <img class="fotoCard" src="source/gpt.png" alt="">
                    </div>
                    <div class="textoCard">
                        estou aqui
                    </div>
                </div>

                <div id="Cards">
                    <div class="fundoFotoCard">
                        <img class="fotoCard" src="source/gpt.png" alt="">
                    </div>
                    <div class="textoCard">
                        estou aqui
                    </div>
                </div>

                <div id="Cards">
                    <div class="fundoFotoCard">
                        <img class="fotoCard" src="source/gpt.png" alt="">
                    </div>
                    <div class="textoCard">
                        estou aqui
                    </div>
                </div>
            </article>
        </section>
</body>
</html>