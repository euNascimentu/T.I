<?php
session_start();

// Verifica se o usuário está logado e se há itens no carrinho
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrinho'])) {
    header('Location: login.php');
    exit();
}

// Calcula o valor total do pedido
$subtotal = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}
$frete = 25.00; // Valor fixo de frete para exemplo
$total = $subtotal + $frete;

// Conexão com o MySQL
$conexao = new mysqli('localhost', 'root', '', 'skateshop');

// Verifica erros de conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Inserir pedido na tabela Pedidos
$sql = "INSERT INTO Pedidos (
    id_usuario, 
    valor_total, 
    frete, 
    forma_pagamento, 
    endereco_entrega, 
    cidade_entrega, 
    estado_entrega, 
    cep_entrega
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da query: " . $conexao->error);
}

// Bind dos parâmetros
$stmt->bind_param(
    "iddsssss", 
    $_SESSION['usuario_id'],    // i (integer)
    $total,                     // d (decimal/double)
    $frete,                     // d (decimal/double)
    $_POST['pagamento'],        // s (string)
    $_POST['endereco'],         // s (string)
    $_POST['cidade'],           // s (string)
    $_POST['estado'],           // s (string)
    $_POST['cep']               // s (string)
);

// Executa a inserção
if ($stmt->execute()) {
    $id_pedido = $conexao->insert_id;
    
    // Agora insere os itens do pedido na tabela ItensPedido
    foreach ($_SESSION['carrinho'] as $id_produto => $item) {
        $sql_item = "INSERT INTO ItensPedido (
            id_pedido, 
            id_produto, 
            quantidade, 
            preco_unitario
        ) VALUES (?, ?, ?, ?)";
        
        $stmt_item = $conexao->prepare($sql_item);
        $stmt_item->bind_param(
            "iiid",
            $id_pedido,
            $id_produto,
            $item['quantidade'],
            $item['preco']
        );
        $stmt_item->execute();
        $stmt_item->close();
    }
    
    // Limpar carrinho
    unset($_SESSION['carrinho']);
    
    // Redirecionar para página de confirmação com o ID do pedido
    header('Location: pedido_confirmado.php?id=' . $id_pedido);
    exit();
} else {
    die("Erro ao executar a query: " . $stmt->error);
}

$stmt->close();
$conexao->close();
?>