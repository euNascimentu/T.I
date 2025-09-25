<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php';

$idUsuario = $_SESSION['usuario']['idUsuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeUsuario = trim($_POST['nomeUsuario']);
    $bioUsuario = trim($_POST['bioUsuario']);

    // Atualizar no banco
    $sql = "UPDATE Usuario SET nomeUsuario = ?, bioUsuario = ? WHERE idUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nomeUsuario, $bioUsuario, $idUsuario);

    if ($stmt->execute()) {
        // Atualizar o nome na sessão para refletir na navbar etc
        $_SESSION['usuario']['nome'] = $nomeUsuario;

        header("Location: perfil.php");
        exit();
    } else {
        echo "Erro ao atualizar perfil: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>