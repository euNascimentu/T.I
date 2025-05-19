<?php
// Configurações do banco de dados
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'skateshop';

try {
    // Conexão com o banco de dados
    $conexao = new mysqli($host, $usuario, $senha, $banco);
    
    // Verifica erros de conexão
    if ($conexao->connect_error) {
        throw new Exception("Erro de conexão: " . $conexao->connect_error);
    }
    
    // Inicia transação
    $conexao->begin_transaction();
    
    // Array de produtos (coloque todos os 30 produtos aqui)
    $produtos = [ ... ]; // Seu array completo de produtos
    
    // Prepara a query de inserção
    $sql = "INSERT INTO Produtos (nome, preco, imagem, descricao, categoria, estoque) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Erro na preparação da query: " . $conexao->error);
    }
    
    // Loop através de cada produto
    foreach ($produtos as $produto) {
        $estoque = 10; // Valor padrão para estoque
        
        // Vincula os parâmetros
        $stmt->bind_param(
            "sdsssi", 
            $produto['nome'],
            $produto['preco'],
            $produto['imagem'],
            $produto['descricao'],
            $produto['categoria'],
            $estoque
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir produto {$produto['nome']}: " . $stmt->error);
        }
    }
    
    // Confirma a transação
    $conexao->commit();
    
    echo "Todos os 30 produtos foram inseridos com sucesso!";
    
} catch (Exception $e) {
    // Desfaz a transação em caso de erro
    if (isset($conexao)) {
        $conexao->rollback();
    }
    die("Erro: " . $e->getMessage());
} finally {
    // Fecha a conexão
    if (isset($stmt)) $stmt->close();
    if (isset($conexao)) $conexao->close();
}
?>