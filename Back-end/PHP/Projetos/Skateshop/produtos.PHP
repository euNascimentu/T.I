<?php
session_start();

// Conexão com o banco
$conexao = new mysqli('localhost', 'root', '', 'skateshop');

// Verifica erros
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Busca produtos no banco
$produtos = [];
$resultado = $conexao->query("SELECT * FROM Produtos ORDER BY nome");

if ($resultado) {
    while ($produto = $resultado->fetch_assoc()) {
        $produtos[] = $produto;
    }
}

// Mostra mensagem de sucesso se existir
$mensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
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
                    <li><a href="home.php">Home</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="home.php#carrinho">Carrinho</a></li>
                    <li><a href="home.php#contato">Contato</a></li>
                </ul>
            </nav>
            <div id="cart-icon">
                <a href="home.php#carrinho">🛒 <span id="cart-count"><?= isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : '0' ?></span></a>
            </div>
        </div>
    </header>

    <main class="container">
        <section>
            <?php if ($mensagem): ?>
                <div class="mensagem-sucesso">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>

            <h2 class="section-title">Todos os Produtos</h2>

            <!-- Filtros por Categoria -->
            <div class="filtros-categoria">
                <!-- ... (mantenha os botões de filtro existentes) ... -->
            </div>

            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card" data-categoria="<?= htmlspecialchars($produto['categoria']) ?>">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>"
                                 onerror="this.onerror=null;this.src='images/sem-imagem.jpg'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            <div class="product-price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <form method="POST" action="adicionar-carrinho.php">
                                <input type="hidden" name="produto_id" value="<?= $produto['id_produto'] ?>">
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