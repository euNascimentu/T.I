<?php
session_start();
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quem Somos | Home.com</title>
    <link rel="stylesheet" href="../CSS/Home.CSS">
    <link rel="stylesheet" href="../CSS/somos.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap');
    </style>
</head>

<body>
    <header>
        <div id="cabecalho">
            <a href="home.php">
                <img class="logoHeader" src="../Source/Logo.png" alt="">
            </a>
            <div class="clicksHeader">
                <a class="refs" href="quemSomos.php">Quem somos?</a>
                <a class="refs" href="home.php">Recursos</a>
                <a class="refs" href="PlanosEprecos.php">Planos e Preços</a>
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

    <main id="quem-somos-container">
        <section class="quem-somos-section">
            <h1>Quem Somos Nós?</h1>
            <h2>Conheça nossa equipe</h2>
            
            <div class="equipe-container">
                <img src="../Source/senac.png" alt="Equipe do projeto no Senac Santos" class="equipe-img">
                
                <div class="texto-equipe">
                    <p>Somos uma equipe dedicada de estudantes do <strong>Senac Santos</strong>, comprometidos em transformar a forma como as pessoas lidam com suas finanças.</p>
                    
                    <p>Nosso projeto integrador nasceu da vontade de ajudar as pessoas a organizarem suas finanças de forma simples e eficiente, eliminando a complexidade das planilhas tradicionais.</p>
                    
                    <p>Com o nosso aplicativo de planejamento e controle financeiro, buscamos oferecer ferramentas intuitivas para que você possa:</p>
                    
                    <ul class="beneficios-list">
                        <li>Gerenciar seus gastos com clareza</li>
                        <li>Traçar metas financeiras realistas</li>
                        <li>Visualizar seu progresso em tempo real</li>
                        <li>Alcançar mais tranquilidade no seu dia a dia</li>
                    </ul>
                    
                    <p>Acreditamos que educação financeira é essencial para uma vida mais equilibrada, e estamos comprometidos em fazer diferença na vida de nossos usuários.</p>
                    
            
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p class="data">©2025</p>
        <div class="redes">
            <a href="">
                <img class="logosRedes" src="../Source/instagram.png" alt="Instagram">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/twitter.png" alt="Twitter">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/linkedin.png" alt="LinkedIn">
            </a>
            <a href="">
                <img class="logosRedes" src="../Source/facebook.png" alt="Facebook">
            </a>
        </div>
        <div class="privacidade">
            <a class="textoPriv" href="">Política de Privacidade</a>
            <a class="textoPriv" href="">Termos de Serviços</a>
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
</body>
</html>