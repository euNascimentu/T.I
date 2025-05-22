<?php
// Conexão com o banco
$conexao = new mysqli('localhost', 'root', '', 'ControleFinanceiro');

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $nome = $conexao->real_escape_string(trim($_POST['nome'] ?? ''));
    $email = $conexao->real_escape_string(trim($_POST['email'] ?? ''));
    $senha = md5(trim($_POST['senha'] ?? ''));

    // Verifica se email já existe
    $verifica = $conexao->prepare("SELECT idUsuario FROM Usuarios WHERE emailUsuario = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        header('Location: registros.php?email=erro');
        exit();
    }

    // Insere o usuário
    $stmt = $conexao->prepare("INSERT INTO Usuarios (nomeUsuario, emailUsuario, senhaUsuario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        header('Location: index.php?usuario=sucesso');
        exit();
    } else {
        header('Location: registros.php?usuario=erro');
        exit();
    }
}
?>
