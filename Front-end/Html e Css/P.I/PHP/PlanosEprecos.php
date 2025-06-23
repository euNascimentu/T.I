<?php
session_start();
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos e Preços | Nome do App</title>
    <link rel="stylesheet" href="../CSS/Home.css"> <!-- CSS base -->
    <link rel="stylesheet" href="../CSS/PlanosEprecos.css"> <!-- CSS desta página -->
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
                        <a class="cadastrar" href='PlanosEprecos.php'>Assinar Agora</a>
                        <a class="entrar" href="index.php">Log In</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main id="planos-container">
        <div class="planos-content">
            <h1>Planos e Preços</h1>
            <h2>Escolha o plano ideal para você</h2>

            <article>
                <div id="escrita">
                    <h3>Plano Básico Gratuito</h3>
                    <ul>
                        <li>Controle de receita e despesas</li>
                        <li>Limite de 3 categorias de gastos</li>
                        <li>Relatórios simples (mensal)</li>
                        <li>1 meta financeira ativa</li>
                        <li>Anúncio não intrusivo</li>
                    </ul>
                </div>
                <div id="fundoBotaoGeral">
                    <div id="botao">
                        <a class="textoBotao" href="">Contrate Já</a>
                    </div>
                </div>
            </article>

            <article>
                <div id="escrita">
                    <h3>Plano Essencial - R$9,90/mês ou R$80/ano</h3>
                    <ul>
                        <li>Todas as funções do básico, sem anúncio</li>
                        <li>Categorias ilimitadas</li>
                        <li>Relatórios detalhados + gráficos personalizados</li>
                        <li>Até 5 metas financeiras</li>
                        <li>Lembretes de contas a pagar</li>
                        <li>Exportação de dados (Excel/PDF)</li>
                    </ul>
                </div>
                <div id="fundoBotaoGeral">
                    <div id="botao">
                        <a class="textoBotao" href="">Contrate Já</a>
                    </div>
                </div>
            </article>

            <article>
                <div id="escrita">
                    <h3>Plano Premium - R$14,90/mês ou R$120/ano</h3>
                    <ul>
                        <li>Tudo do Essencial+</li>
                        <li>Orçamento por projetos</li>
                        <li>Acesso a webinars</li>
                        <li>Integração com bancos</li>
                        <li>Prioridade de suporte</li>
                    </ul>
                </div>
                <div id="fundoBotaoGeral">
                    <div id="botao">
                        <a class="textoBotao" href="">Contrate Já</a>
                    </div>
                </div>
            </article>

            <article id="DuoCasal">
                <div id="cabecalhoPlanoConjunto">
                    <h3>Planos Conjuntos</h3>
                </div>
                <div id="casal">
                    <div id="conjunto">
                        <h4>Plano Duo/Casal - Adicional de R$18,90 por mês</h4>
                        <ul>
                            <li>2 usuários</li>
                            <li>Visão compartilhada</li>
                            <li>Metas conjuntas</li>
                        </ul>
                    </div>
                    <div id="fundoBotao">
                        <div id="botaoCasal">
                            <a class="textoBotao" href="">Contrate Já</a>
                        </div>
                    </div>
                </div>

                <div id="familia">
                    <div id="conjunto">
                        <h4>Plano Família - Adicional de R$24,90 por mês</h4>
                        <ul>
                            <li>Até 4 usuários</li>
                            <li>Controle financeiro para pais e filhos</li>
                            <li>Mesada digital com acompanhamento</li>
                        </ul>
                    </div>
                    <div id="fundoBotao">
                        <div id="botaoFamilia">
                            <a class="textoBotao" href="">Contrate Já</a>
                        </div>
                    </div>
                </div>
            </article>

            <article>
                <div id="escrita">
                    <h3>Diferenciais</h3>
                    <ul>
                        <li>Desconto para estudantes (30% off)</li>
                        <li>Indique um amigo e ganhe 1 mês grátis (Essencial)</li>
                    </ul>
                </div>
            </article>
        </div>
    </main>

    <footer>
        <p class="data">©2025</p>
        <div class="redes">
            <a href="#"><img class="logosRedes" src="../Source/instagram.png" alt=""></a>
            <a href="#"><img class="logosRedes" src="../Source/twitter.png" alt=""></a>
            <a href="#"><img class="logosRedes" src="../Source/linkedin.png" alt=""></a>
            <a href="#"><img class="logosRedes" src="../Source/facebook.png" alt=""></a>
        </div>
        <div class="privacidade">
            <a class="textoPriv" href="#">Política de Privacidade</a>
            <a class="textoPriv" href="#">Termos de Serviço</a>
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
