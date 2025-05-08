<?php
session_start();

// Verifica se os dados do produto foram enviados
if (isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    
    // Inclui os dados dos produtos
    include 'produtos-data.php';
    
    // Encontra o produto na lista
    $produto_adicionado = null;
    foreach ($produtos as $produto) {
        if ($produto['id'] == $produto_id) {
            $produto_adicionado = $produto;
            break;
        }
    }
    
    // Se encontrou o produto, adiciona ao carrinho
    if ($produto_adicionado) {
        // Inicializa o carrinho se não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        // Verifica se o produto já está no carrinho
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produto_id] = [
                'nome' => $produto_adicionado['nome'],
                'preco' => $produto_adicionado['preco'],
                'imagem' => $produto_adicionado['imagem'],
                'quantidade' => $quantidade
            ];
        }
        
        // Redireciona de volta para a página de produtos com mensagem de sucesso
        $_SESSION['mensagem'] = "Produto adicionado ao carrinho!";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// Se algo der errado, redireciona de volta
header('Location: produtos.php');
exit();
?>