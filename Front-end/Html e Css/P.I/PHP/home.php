<?php
session_start();
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Home.CSS">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </style>
    <title> Home.com </title>
</head>

<body>
    <header>
        <div id="cabecalho">
            <a href="home.php">
                <img class="logoHeader" src="../Source/Logo.png" alt="">
            </a>
            <div class="clicksHeader">
                <a class="refs" href="quemSomos.php"> Quem somos? </a>
                <a class="refs" href=""> Recursos </a>
                <a class="refs" href="PlanosEprecos.php"> Planos e Preços </a>
            </div>
            <div class="botoesHeader">
    <div id="user-area">
        <?php if (isset($_SESSION['usuario_nome'])): ?>
            <div class="dropdown">
                <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario_nome']) ?> ▼</button>
                <div class="dropdown-content">
                    <a href="logout.php">Sair</a>
                </div>
            </div>
        <?php else: ?>
            <a class="entrar" href="index.php">Log In</a>
            <a class="cadastrar" href='registros.php'>Assinar Agora</a>
        <?php endif; ?>
    </div>
</div>

        </div>
    </header>
    <section>
        <div id="Textos">
            <div id="Titulo">
                <P>Controle financeiro pessoal com toda
                <h3 class="CorFundo">praticidade</h3> que
                a
                planilha não te oferece!</P>
            </div>
            <div id="subTitulo">
                <p>
                    Organize seu dinheiro em tempo real em uma solução completa, prática e segura. Tenha o controle de
                    finanças que você sempre quis!
                </p>
            </div>
            <div id="botao">
                <a class="assineJa" href="registros.php">
                    <p style="font-size: 25px;">Assine Já</p>
                    <img style="width: 30px; height: 30px;" src="../Source/saida.png" alt="">
                </a>
            </div>
            <div style="display: flex; flex-flow: wrap row; width: 80%;">
                <div style="width: 330px; display: flex; align-items: center; justify-content: space-evenly;">
                    <img style="width: 40px;" src="../Source/trancar.png" alt="">
                    <p style="width: 40vw; font-size: 20px;">Segurança dos seus dados em primeiro lugar</p>
                    </div>
                    <div style="width: 330px;display: flex; align-items: center; justify-content: space-evenly;">
                        <img style="width: 40px;" src="../Source/dispositivos.png" alt="">
                        <p style="width: 40vw; font-size: 20px;">Acesse quando quiser, no celular ou computador</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="fundoBoneco">
            <img class="imgBoneco" src="../Source/controle_financeiro_homem-removebg-preview.png" alt="">
        </div>
    </section>
    <footer>
        <p class="data">
            ©2025
        </p>
        <div class="redes">
            <a href="">
                <img class="logosRedes" src="../Source/instagram.png" alt="">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/twitter.png" alt="">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/linkedin.png" alt="">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/facebook.png" alt="">
            </a>
        </div>
        <div class="privacidade">
            <a class="textoPriv" href=""> Política de Privacidade </a>
            <a class="textoPriv" href=""> Termos de Serviços</a>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropbtn = document.querySelector('.dropbtn');
        const dropdown = document.querySelector('.dropdown-content');

        if (dropbtn && dropdown) {
            dropbtn.addEventListener('click', function () {
                dropdown.classList.toggle('show');
            });

            // Fecha o dropdown ao clicar fora
            window.addEventListener('click', function (event) {
                if (!event.target.matches('.dropbtn')) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            });
        }
    });
</script>
</html>