<?php
session_start();

// Conexão com o banco
$conexao = new mysqli('localhost', 'root', '', 'skateshop');

// Verifica erros
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'] ?? 0;
    $quantidade = $_POST['quantidade'] ?? 1;

    // Busca o produto no banco
    $sql = "SELECT id_produto, nome, preco, imagem FROM Produtos WHERE id_produto = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $produto = $resultado->fetch_assoc();

        // Inicializa o carrinho se não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Adiciona ou atualiza o item no carrinho
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produto_id] = [
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'imagem' => $produto['imagem'],
                'quantidade' => $quantidade
            ];
        }

        $_SESSION['mensagem'] = "Produto adicionado ao carrinho!";
    }
}

header('Location: produtos.php');
exit();
?>