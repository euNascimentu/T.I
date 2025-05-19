<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se foi passado um ID de pedido
if (!isset($_GET['id'])) {
    header('Location: home.php');
    exit();
}

$id_pedido = intval($_GET['id']);

// Conexão com o banco de dados
$conexao = new mysqli('localhost', 'root', '', 'skateshop');

// Verifica erros de conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Busca os dados do pedido
$sql_pedido = "SELECT * FROM Pedidos WHERE id_pedido = ? AND id_usuario = ?";
$stmt_pedido = $conexao->prepare($sql_pedido);
$stmt_pedido->bind_param("ii", $id_pedido, $_SESSION['usuario_id']);
$stmt_pedido->execute();
$resultado_pedido = $stmt_pedido->get_result();

if ($resultado_pedido->num_rows === 0) {
    header('Location: home.php');
    exit();
}

$pedido = $resultado_pedido->fetch_assoc();

// Busca os itens do pedido
$sql_itens = "SELECT ip.*, p.nome, p.imagem 
              FROM ItensPedido ip
              JOIN Produtos p ON ip.id_produto = p.id_produto
              WHERE ip.id_pedido = ?";
$stmt_itens = $conexao->prepare($sql_itens);
$stmt_itens->bind_param("i", $id_pedido);
$stmt_itens->execute();
$itens_pedido = $stmt_itens->get_result();

// Formata a data do pedido
$data_pedido = date('d/m/Y H:i', strtotime($pedido['data_pedido']));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - SkateShop</title>
    <link rel="stylesheet" href="CSS/style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@500&display=swap" rel="stylesheet">
    <style>
        .order-confirmation {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .confirmation-header h1 {
            color: #2ecc71;
            margin-bottom: 10px;
        }
        
        .confirmation-number {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        
        .order-details {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .order-section {
            flex: 1;
            min-width: 250px;
        }
        
        .order-section h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            color: #333;
        }
        
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .order-items th {
            text-align: left;
            padding: 10px;
            background-color: #f5f5f5;
            border-bottom: 2px solid #ddd;
        }
        
        .order-items td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-items .item-image {
            width: 80px;
        }
        
        .order-items .item-image img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .order-totals {
            margin-top: 20px;
            text-align: right;
        }
        
        .order-totals table {
            display: inline-block;
        }
        
        .order-totals td {
            padding: 8px 15px;
        }
        
        .order-totals .total-row {
            font-weight: bold;
            font-size: 1.1em;
            border-top: 2px solid #ddd;
        }
        
        .next-steps {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .next-steps h3 {
            margin-bottom: 15px;
        }
        
        .next-steps ul {
            list-style-type: none;
            padding-left: 20px;
        }
        
        .next-steps li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 25px;
        }
        
        .next-steps li:before {
            content: "✓";
            color: #2ecc71;
            position: absolute;
            left: 0;
        }
        
        .btn-continue {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 30px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn-continue:hover {
            background-color: #0055aa;
        }
        
        .payment-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
            border-left: 4px solid #0066cc;
        }
    </style>
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
                <li><a href="produtos.PHP" target="_self">Produtos</a></li>
                <li><a href="home.php#carrinho">Carrinho</a></li>
                <li><a href="home.php#contato">Contato</a></li>
            </ul>
        </nav>
        <div id="cart-icon">
            <a href="#carrinho">🛒 <span id="cart-count">
                <?= !empty($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0 ?>
            </span></a>
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
    </div>
</header>

    <main class="container">
        <div class="order-confirmation">
            <div class="confirmation-header">
                <h1>Pedido Confirmado!</h1>
                <div class="confirmation-number">
                    Número do pedido: <strong>#<?= str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) ?></strong>
                </div>
                <p>Obrigado por comprar na SkateShop! Seu pedido foi recebido com sucesso.</p>
            </div>
            
            <div class="order-details">
                <div class="order-section">
                    <h2>Informações do Pedido</h2>
                    <p><strong>Data:</strong> <?= $data_pedido ?></p>
                    <p><strong>Status:</strong> 
                        <span style="color: 
                            <?= $pedido['status_pedido'] == 'pendente' ? '#e67e22' : 
                              ($pedido['status_pedido'] == 'enviado' ? '#3498db' : 
                              ($pedido['status_pedido'] == 'entregue' ? '#2ecc71' : '#555')) ?>">
                            <?= ucfirst($pedido['status_pedido']) ?>
                        </span>
                    </p>
                    <p><strong>Forma de pagamento:</strong> 
                        <?= $pedido['forma_pagamento'] == 'cartao' ? 'Cartão de Crédito' : 
                          ($pedido['forma_pagamento'] == 'pix' ? 'PIX' : 'Boleto Bancário') ?>
                    </p>
                </div>
                
                <div class="order-section">
                    <h2>Endereço de Entrega</h2>
                    <p><?= htmlspecialchars($pedido['endereco_entrega']) ?></p>
                    <p><?= htmlspecialchars($pedido['cidade_entrega']) ?> - <?= $pedido['estado_entrega'] ?></p>
                    <p>CEP: <?= $pedido['cep_entrega'] ?></p>
                </div>
            </div>
            
            <h2>Itens do Pedido</h2>
            <table class="order-items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Preço Unitário</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $itens_pedido->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="item-image">
                                    <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                </div>
                                <div>
                                    <?= htmlspecialchars($item['nome']) ?>
                                </div>
                            </div>
                        </td>
                        <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div class="order-totals">
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td>R$ <?= number_format($pedido['valor_total'] - $pedido['frete'], 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Frete:</td>
                        <td>R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></td>
                    </tr>
                    <tr class="total-row">
                        <td>Total:</td>
                        <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
            
            <?php if ($pedido['forma_pagamento'] == 'boleto'): ?>
            <div class="payment-info">
                <h3>Pagamento por Boleto Bancário</h3>
                <p>O boleto será enviado para o e-mail <strong><?= $_SESSION['usuario_email'] ?? '' ?></strong> em até 10 minutos.</p>
                <p>O prazo para pagamento é de 2 dias úteis.</p>
            </div>
            <?php elseif ($pedido['forma_pagamento'] == 'pix'): ?>
            <div class="payment-info">
                <h3>Pagamento por PIX</h3>
                <p>As instruções para pagamento via PIX foram enviadas para o e-mail <strong><?= $_SESSION['usuario_email'] ?? '' ?></strong>.</p>
                <p>O pedido será processado após a confirmação do pagamento.</p>
            </div>
            <?php endif; ?>
            
            <div class="next-steps">
                <h3>Próximos Passos</h3>
                <ul>
                    <li>Você receberá um e-mail com os detalhes do seu pedido</li>
                    <li>O prazo de entrega é de até 5 dias úteis após a confirmação do pagamento</li>
                    <li>Acompanhe seu pedido na seção "Meus Pedidos"</li>
                    <li>Em caso de dúvidas, entre em contato conosco</li>
                </ul>
                
                <a href="produtos.php" class="btn-continue">Continuar Comprando</a>
            </div>
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
<?php
$stmt_pedido->close();
$stmt_itens->close();
$conexao->close();
?>