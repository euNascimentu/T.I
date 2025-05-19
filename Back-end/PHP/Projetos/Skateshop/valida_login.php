<?php
session_start();

$conexao = new mysqli('localhost', 'root', '', 'skateshop');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conexao->real_escape_string(trim($_POST['email']));
    $senha = trim($_POST['senha']);
    
    $stmt = $conexao->prepare("SELECT id_usuario as id, nome, email, senha 
                              FROM usuarios 
                              WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Comparação segura com MD5
        if (hash_equals($usuario['senha'], md5($senha))) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
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