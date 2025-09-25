<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php';

$idUsuario = $_SESSION['usuario']['idUsuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idJogo = intval($_POST['idJogo']);

    // Verificar se já existe
    $sqlCheck = "SELECT * FROM Favorito WHERE idUsuario = ? AND idJogo = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $idUsuario, $idJogo);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 0) {
        // Inserir favorito
        $sqlInsert = "INSERT INTO Favorito (idUsuario, idJogo) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("ii", $idUsuario, $idJogo);

        if ($stmtInsert->execute()) {
            header("Location: perfil.php");
            exit();
        } else {
            echo "Erro ao adicionar favorito: " . $conn->error;
        }

        $stmtInsert->close();
    } else {
        // Já existe
        header("Location: perfil.php");
        exit();
    }

    $stmtCheck->close();
}

$conn->close();
?>
