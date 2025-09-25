<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão']);
    exit();
}
$conn->set_charset("utf8");

// Obter ID do usuário
if (isset($_SESSION['usuario']['idUsuario'])) {
    $idUsuario = $_SESSION['usuario']['idUsuario'];
} elseif (isset($_SESSION['usuario']['id'])) {
    $idUsuario = $_SESSION['usuario']['id'];
} else {
    echo json_encode(['success' => false, 'message' => 'ID do usuário não encontrado']);
    exit();
}

// Processar a ação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $idJogo = $_POST['idJogo'] ?? 0;
    $tipoSalvar = $_POST['tipoSalvar'] ?? 0;
    
    if (empty($acao) || $idJogo <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        exit();
    }
    
    switch ($acao) {
        case 'favorito':
            $result = gerenciarJogoSalvo($conn, $idUsuario, $idJogo, 2);
            break;
            
        case 'quero_jogar':
            $result = gerenciarJogoSalvo($conn, $idUsuario, $idJogo, 1);
            break;
            
        case 'jogando':
            $result = gerenciarJogoSalvo($conn, $idUsuario, $idJogo, 3);
            break;
            
        case 'completado':
            $result = gerenciarJogoSalvo($conn, $idUsuario, $idJogo, 4);
            break;
            
        case 'remover':
            $result = removerJogoSalvo($conn, $idUsuario, $idJogo, $tipoSalvar);
            break;
            
        default:
            $result = ['success' => false, 'message' => 'Ação inválida'];
    }
    
    echo json_encode($result);
    exit();
}

function gerenciarJogoSalvo($conn, $idUsuario, $idJogo, $tipoSalvar) {
    // Verificar se já existe
    $sqlCheck = "SELECT idSalvarJogos FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("iii", $idUsuario, $idJogo, $tipoSalvar);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    
    if ($resultCheck->num_rows > 0) {
        // Já existe, então remove
        $sqlDelete = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("iii", $idUsuario, $idJogo, $tipoSalvar);
        $success = $stmtDelete->execute();
        $stmtDelete->close();
        
        return ['success' => $success, 'action' => 'removed', 'tipo' => $tipoSalvar];
    } else {
        // Remove de outras categorias primeiro (para evitar duplicidade)
        $sqlDeleteOther = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ?";
        $stmtDeleteOther = $conn->prepare($sqlDeleteOther);
        $stmtDeleteOther->bind_param("ii", $idUsuario, $idJogo);
        $stmtDeleteOther->execute();
        $stmtDeleteOther->close();
        
        // Adiciona na nova categoria
        $sqlInsert = "INSERT INTO SalvarJogos (idUsuario, idJogo, idTipoSalvar) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("iii", $idUsuario, $idJogo, $tipoSalvar);
        $success = $stmtInsert->execute();
        $stmtInsert->close();
        
        return ['success' => $success, 'action' => 'added', 'tipo' => $tipoSalvar];
    }
}

function removerJogoSalvo($conn, $idUsuario, $idJogo, $tipoSalvar) {
    $sql = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ?";
    if ($tipoSalvar > 0) {
        $sql .= " AND idTipoSalvar = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $idUsuario, $idJogo, $tipoSalvar);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $idJogo);
    }
    
    $success = $stmt->execute();
    $stmt->close();
    
    return ['success' => $success, 'action' => 'removed'];
}

$conn->close();
?>