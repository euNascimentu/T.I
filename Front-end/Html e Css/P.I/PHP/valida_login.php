<?php
session_start();

// Conexão com o banco de dados
$conexao = new mysqli('localhost', 'root', '', 'ControleFinanceiro');

// Verifica se a conexão falhou
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Se o formulário for enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conexao->real_escape_string(trim($_POST['email']));
    $senha = trim($_POST['senha']);
    
    // Consulta segura com prepared statement
    $stmt = $conexao->prepare("SELECT idUsuario, nomeUsuario, emailUsuario, senhaUsuario 
                               FROM Usuarios 
                               WHERE emailUsuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verifica se o hash MD5 da senha bate
        if (hash_equals($usuario['senhaUsuario'], md5($senha))) {
            $_SESSION['usuario_id'] = $usuario['idUsuario'];
            $_SESSION['usuario_nome'] = $usuario['nomeUsuario'];
            header('Location: home.php');
            exit();
        } else {
            header('Location: login.php?erro=senha');
            exit();
        }
    } else {
        header('Location: login.php?erro=usuario');
        exit();
    }
}
?>