<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se há itens no carrinho
if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit();
}

// Dados do usuário (você pode buscar no banco se necessário)
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_email = $_SESSION['usuario_email'] ?? '';

// Calcula totais
$subtotal = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}
$frete = 25.00; // Valor fixo de frete para exemplo
$total = $subtotal + $frete;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - SkateShop</title>
    <link rel="stylesheet" href="CSS/style.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <!-- Inclua o mesmo header da home.php -->
        <div class="container">
            <div id="logo">
                <h1><a href="index.php">Skate<span>Shop</span></a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="produtos.PHP" target="_self">Produtos</a></li>
                    <li><a href="#carrinho">Carrinho</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
            </nav>
            <div id="cart-icon">
                <a href="#carrinho">🛒 <span id="cart-count"><?= count($_SESSION['carrinho']) ?></span></a>
            </div>
            <div id="user-area">
                <?php if (isset($_SESSION['usuario_nome'])): ?>
                <div class="dropdown">
                    <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario_nome']) ?> ▼</button>
                <div class="dropdown-content">
                    <a href="perfil.php">Meu Perfil</a>
                    <a href="pedidos.php">Meus Pedidos</a>
                    <a href="logout.php">Sair</a>
                </div>
            </div>
            <?php else: ?>
                <a href="login.php">Log In</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <h1 class="section-title">Finalizar Compra</h1>
        
        <div class="checkout-container">
            <section class="checkout-summary">
                <h2>Resumo do Pedido</h2>
                <ul class="order-items">
                    <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>
                    <li>
                        <span class="item-name"><?= htmlspecialchars($item['nome']) ?></span>
                        <span class="item-quantity"><?= $item['quantidade'] ?>x</span>
                        <span class="item-price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                    </div>
                    <div class="total-row">
                        <span>Frete:</span>
                        <span>R$ <?= number_format($frete, 2, ',', '.') ?></span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                </div>
            </section>

            <section class="checkout-form">
                <h2>Informações de Entrega</h2>
                <form action="processar_pedido.php" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario_nome) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario_email) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado" required>
                                <option value="">Selecione</option>
                                <option value="SP">São Paulo</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <!-- Adicione outros estados -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" required>
                        </div>
                    </div>
                    
                    <h2>Método de Pagamento</h2>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="pagamento" value="cartao" checked>
                            <span>Cartão de Crédito</span>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="pagamento" value="pix">
                            <span>PIX</span>
                        </label>
                        
                        <label class="payment-method">
                            <input type="radio" name="pagamento" value="boleto">
                            <span>Boleto Bancário</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-confirm">Confirmar Pedido</button>
                    </div>
                </form>
            </section>
        </div>
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
                    <li><a href="#home">Home</a></li>
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#contato">Contato</a></li>
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
</body>
</html>