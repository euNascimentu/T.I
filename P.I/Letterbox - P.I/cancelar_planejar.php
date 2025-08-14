<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php';

$idUsuario = $_SESSION['usuario']['idUsuario'];
$idJogo = $_POST['idJogo'] ?? null;

if ($idJogo) {
    $sql = "DELETE FROM Avaliacao WHERE idUsuario = ? AND idJogo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idUsuario, $idJogo);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: perfil.php");
exit();
