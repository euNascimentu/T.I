<?php
$host = "localhost";      // Servidor do banco
$usuario = "root";        // Usuário MySQL
$senha = "";              // Senha MySQL (troque se tiver)
$banco = "PIBD";          // Nome do banco

$conn = new mysqli($host, $usuario, $senha, $banco);

// Testar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8"); // Evitar problemas com acentos
?>
