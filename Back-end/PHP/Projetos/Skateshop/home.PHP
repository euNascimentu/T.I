<?php
// Início da sessão para o carrinho
session_start();

// Mostra mensagem de sucesso se existir
$mensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}

// Dados dos produtos (simulando um banco de dados)
$produtos = [
    [
        'id' => 1,
        'nome' => 'Skate Completo Element',
        'preco' => 499.90,
        'imagem' => 'images/skate1.jpg',
        'descricao' => 'Skate completo da marca Element, perfeito para iniciantes e profissionais.'
    ],
    [
        'id' => 2,
        'nome' => 'Shape Santa Cruz',
        'preco' => 299.90,
        'imagem' => 'images/skate2.jpg',
        'descricao' => 'Shape profissional da Santa Cruz, madeira canadense de alta qualidade.'
    ],
    [
        'id' => 3,
        'nome' => 'Truck Independent',
        'preco' => 249.90,
        'imagem' => 'images/skate3.jpg',
        'descricao' => 'Trucks Independent originais, durabilidade e performance.'
    ],
    [
        'id' => 4,
        'nome' => 'Rodas Spitfire',
        'preco' => 199.90,
        'imagem' => 'images/skate4.jpg',
        'descricao' => 'Rodas Spitfire Formula Four, 54mm 99a.'
    ]
];

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona item ao carrinho
if (isset($_POST['adicionar_carrinho'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'] ?? 1;

    // Verifica se o produto já está no carrinho
    if (isset($_SESSION['carrinho'][$produto_id])) {
        $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
    } else {
        // Encontra o produto na lista
        foreach ($produtos as $produto) {
            if ($produto['id'] == $produto_id) {
                $_SESSION['carrinho'][$produto_id] = [
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => $quantidade
                ];
                break;
            }
        }
    }
}

// Remove item do carrinho
if (isset($_GET['remover'])) {
    $produto_id = $_GET['remover'];
    if (isset($_SESSION['carrinho'][$produto_id])) {
        unset($_SESSION['carrinho'][$produto_id]);
    }
}

// Calcula total do carrinho
function calcularTotal()
{
    $total = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
    return $total;
}

function salvarChamado()
{
    // Dados do formulário
    $dados = [
        'data' => date('d/m/Y H:i:s'),
        'nome' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'assunto' => $_POST['subject'] ?? '',
        'mensagem' => $_POST['message'] ?? ''
    ];

    // Validação básica
    if (empty($dados['nome']) || empty($dados['email']) || empty($dados['mensagem'])) {
        return ['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios!'];
    }

    // Formata os dados para salvar
    $conteudo = "========== NOVO CHAMADO ==========\n";
    foreach ($dados as $campo => $valor) {
        $conteudo .= ucfirst($campo) . ": " . strip_tags($valor) . "\n";
    }
    $conteudo .= "\n";

    // Caminho do arquivo (crie a pasta "data" se não existir)
    $arquivo = 'data/chamados.txt';

    // Garante que a pasta existe
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }

    // Tenta salvar no arquivo
    if (file_put_contents($arquivo, $conteudo, FILE_APPEND | LOCK_EX) !== false) {
        return ['sucesso' => true, 'mensagem' => 'Mensagem enviada com sucesso!'];
    } else {
        return ['sucesso' => false, 'mensagem' => 'Erro ao salvar o chamado. Tente novamente.'];
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateShop - Tudo para seu skate</title>
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
        </div>
    </header>

    <main class="container">
        <?php if ($mensagem): ?>
            <div class="mensagem-sucesso">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <!-- Seção Home -->
        <section id="home" class="hero">
            <div class="hero-content">
                <h2>Tudo para seu skate</h2>
                <p>As melhores marcas e os melhores preços você encontra aqui na SkateShop</p>
                <a href="produtos.PHP" class="btn">Ver Produtos</a>
            </div>
        </section>

        <!-- Seção Produtos -->
        <section id="destaques">
            <h2 class="section-title">Destaques</h2>
            <div class="products-grid">
                <!-- Skate Completo -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/skate-completo-1.jpg" alt="Skate Completo Element"
                            onerror="this.onerror=null;this.src='images/sem-imagem.jpg'"
                            style="width:100%;height:200px;object-fit:cover;">
                    </div>
                    <div class="product-info">
                        <h3>Skate Completo Element</h3>
                        <div class="product-price">R$ 499,90</div>
                    </div>
                </div>

                <!-- Rodas -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/rodas-1.jpg" alt="Rodas Spitfire"
                            onerror="this.onerror=null;this.src='images/sem-imagem.jpg'"
                            style="width:100%;height:200px;object-fit:cover;">
                    </div>
                    <div class="product-info">
                        <h3>Rodas Spitfire</h3>
                        <div class="product-price">R$ 199,90</div>
                    </div>
                </div>

                <!-- Shape -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/shape-2.jpg" alt="Shape Santa Cruz"
                            onerror="this.onerror=null;this.src='images/sem-imagem.jpg'"
                            style="width:100%;height:200px;object-fit:cover;">
                    </div>
                    <div class="product-info">
                        <h3>Shape Santa Cruz</h3>
                        <div class="product-price">R$ 299,90</div>
                    </div>
                </div>

                <!-- Truck -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/truck-1.jpg" alt="Truck Independent"
                            onerror="this.onerror=null;this.src='images/sem-imagem.jpg'"
                            style="width:100%;height:200px;object-fit:cover;">
                    </div>
                    <div class="product-info">
                        <h3>Truck Independent</h3>
                        <div class="product-price">R$ 249,90</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Carrinho -->
        <section id="carrinho">
            <h2 class="section-title">Seu Carrinho</h2>

            <?php if (empty($_SESSION['carrinho'])): ?>
                <p style="text-align: center; margin: 50px 0;">Seu carrinho está vazio</p>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>" class="cart-item-image">
                                        <span style="margin-left: 15px;"><?= $item['nome'] ?></span>
                                    </div>
                                </td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td><?= $item['quantidade'] ?></td>
                                <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                                <td><a href="?remover=<?= $id ?>#carrinho" class="remove-item">✕</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-totals">
                    <h3>Resumo do Pedido</h3>
                    <table>
                        <tr>
                            <td>Subtotal</td>
                            <td>R$ <?= number_format(calcularTotal(), 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Frete</td>
                            <td>R$ 25,00</td>
                        </tr>
                        <tr>
                            <td class="total">Total</td>
                            <td class="total">R$ <?= number_format(calcularTotal() + 25, 2, ',', '.') ?></td>
                        </tr>
                    </table>
                    <button class="checkout-btn"><a class="textFinalizar" href="finalizar_compra.php">Finalizar Compra</a></button>
                </div>
            <?php endif; ?>
        </section>
        <section id="contato">
            <h2 class="section-title">Entre em Contato</h2>
            <div class="contact-form">
                <?php
                // Verifica se o formulário foi enviado
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
                    $resultado = salvarChamado();
                    echo '<div class="' . ($resultado['sucesso'] ? 'mensagem-sucesso' : 'mensagem-erro') . '">' . $resultado['mensagem'] . '</div>';
                }
                ?>

                <form method="POST" action="#contato">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" id="name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Assunto</label>
                        <input type="text" id="subject" name="subject" value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Mensagem</label>
                        <textarea id="message" name="message" required><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Enviar Mensagem</button>
                </form>
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

    <script>
    // JavaScript incorporado para funcionalidades básicas
    document.addEventListener('DOMContentLoaded', function() {
        // Atualiza contador do carrinho
        function updateCartCount() {
            const count = <?= count($_SESSION['carrinho']) ?>;
            document.getElementById('cart-count').textContent = count;
        }

        // Adiciona smooth scroll para os links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // ===== NOVAS FUNCIONALIDADES ADICIONADAS =====
        
        // Limpa o formulário de contato após envio bem-sucedido
        const formContato = document.getElementById('formContato');
        if (formContato) {
            formContato.addEventListener('submit', function(e) {
                // Desabilita o botão durante o envio
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';
                
                // Se for um envio bem-sucedido, limpa os campos
                setTimeout(() => {
                    if (document.querySelector('.mensagem-sucesso')) {
                        this.reset();
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Enviar Mensagem';
                }, 1000);
            });
        }

        // Adiciona efeito visual ao botão de submit
        const submitBtn = document.querySelector('.submit-btn');
        if (submitBtn) {
            submitBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            submitBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });
</script>
</body>

</html>