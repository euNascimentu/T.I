<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php';

$idUsuario = $_SESSION['usuario']['idUsuario'];
$idJogo = $_POST['idJogo'] ?? null;
$nomeJogo = $_POST['nomeJogo'] ?? null;

if (!$idJogo || !$nomeJogo) {
    die("Dados inválidos.");
}

// Verifica se o jogo já existe na tabela Jogo
$sql_check_jogo = "SELECT idJogo FROM Jogo WHERE idJogo = ?";
$stmt = $conn->prepare($sql_check_jogo);
$stmt->bind_param("i", $idJogo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insere o jogo se não existir
    $sql_insert_jogo = "INSERT INTO Jogo (idJogo, nomeJogo) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_jogo);
    $stmt_insert->bind_param("is", $idJogo, $nomeJogo);
    $stmt_insert->execute();
    $stmt_insert->close();
}

// Verifica se o jogo já foi adicionado como planejado (Avaliacao)
$sql_check_planejado = "SELECT * FROM Avaliacao WHERE idUsuario = ? AND idJogo = ?";
$stmt_check = $conn->prepare($sql_check_planejado);
$stmt_check->bind_param("ii", $idUsuario, $idJogo);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows === 0) {
    // Adiciona à tabela Avaliacao como planejado, sem nota e comentário
    $sql_insert_planejado = "INSERT INTO Avaliacao (idUsuario, idJogo) VALUES (?, ?)";
    $stmt_insert_planejado = $conn->prepare($sql_insert_planejado);
    $stmt_insert_planejado->bind_param("ii", $idUsuario, $idJogo);
    $stmt_insert_planejado->execute();
    $stmt_insert_planejado->close();
}

$stmt->close();
$stmt_check->close();
$conn->close();

header("Location: perfil.php");
exit();
