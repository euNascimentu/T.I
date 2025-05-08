<?php
session_start();
include 'produtos-data.php'; // Inclui os dados dos produtos

// Mostra mensagem de sucesso se existir
$mensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']); // Remove a mensagem para não mostrar novamente
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | SkateShop</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@500&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container">
            <div id="logo">
                <h1><a href="index.php">Skate<span>Shop</span></a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="index.php#carrinho">Carrinho</a></li>
                    <li><a href="index.php#contato">Contato</a></li>
                </ul>
            </nav>
            <div id="cart-icon">
                <a href="index.php#carrinho">🛒 <span id="cart-count"><?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '0' ?></span></a>
            </div>
        </div>
    </header>

    <main class="container">
        <section>
            <!-- Adicione esta div para mostrar mensagens -->
            <?php if ($mensagem): ?>
                <div class="mensagem-sucesso">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <h2 class="section-title">Todos os Produtos</h2>

            <!-- Filtros por Categoria -->
            <div class="filtros-categoria">
                <button class="btn-filtro active" data-categoria="todos">Todos</button>
                <button class="btn-filtro" data-categoria="completo">Skates Completos</button>
                <button class="btn-filtro" data-categoria="shape">Shapes</button>
                <button class="btn-filtro" data-categoria="truck">Trucks</button>
                <button class="btn-filtro" data-categoria="rodas">Rodas</button>
                <button class="btn-filtro" data-categoria="rolamentos">Rolamentos</button>
                <button class="btn-filtro" data-categoria="lixa">Lixas</button>
                <button class="btn-filtro" data-categoria="vestuario">Vestuário</button>
                <button class="btn-filtro" data-categoria="tenis">Tênis</button>
                <button class="btn-filtro" data-categoria="acessorios">Acessórios</button>
                <button class="btn-filtro" data-categoria="longboard">Longboards</button>
            </div>

            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card" data-categoria="<?= $produto['categoria'] ?>">
                        <div class="product-image">
                            <img src="<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>">
                        </div>
                        <div class="product-info">
                            <h3><?= $produto['nome'] ?></h3>
                            <div class="product-price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <form method="POST" action="adicionar-carrinho.php">
                                <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                <input type="number" name="quantidade" value="1" min="1" style="width: 60px; margin-bottom: 10px;">
                                <button type="submit" class="add-to-cart">Adicionar ao Carrinho</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Sobre Nós</h3>
                <p>A SkateShop é a maior loja de skate online do Brasil, oferecendo os melhores produtos para skatistas desde 2010.</p>
            </div>
            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="index.php#contato">Contato</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contato</h3>
                <p>contato@skateshop.com</p>
                <p>(11) 99999-9999</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 SkateShop. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Filtro por categoria
        document.querySelectorAll('.btn-filtro').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove a classe 'active' de todos os botões
                document.querySelectorAll('.btn-filtro').forEach(b => b.classList.remove('active'));
                // Adiciona a classe 'active' apenas no botão clicado
                this.classList.add('active');

                const categoria = this.dataset.categoria;

                // Mostra/oculta produtos conforme a categoria selecionada
                document.querySelectorAll('.product-card').forEach(card => {
                    if (categoria === 'todos' || card.dataset.categoria === categoria) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>