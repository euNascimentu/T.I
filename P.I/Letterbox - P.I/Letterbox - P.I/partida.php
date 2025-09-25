<?php
session_start();

// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) die("Falha na conexão: " . $conn->connect_error);
$conn->set_charset("utf8");

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['usuario']['idUsuario'];
$nomeUsuario = $_SESSION['usuario']['nome'];

// Buscar todos os jogos
$sqlJogos = "SELECT * FROM Jogo ORDER BY nomeJogo";
$resultJogos = $conn->query($sqlJogos);
$jogos = $resultJogos->fetch_all(MYSQLI_ASSOC);

// Buscar todos os usuários (exceto o atual)
$sqlUsuarios = "SELECT idUsuario, nomeUsuario FROM Usuario WHERE idUsuario != ? ORDER BY nomeUsuario";
$stmtUsuarios = $conn->prepare($sqlUsuarios);
$stmtUsuarios->bind_param("i", $idUsuario);
$stmtUsuarios->execute();
$resultUsuarios = $stmtUsuarios->get_result();
$usuarios = $resultUsuarios->fetch_all(MYSQLI_ASSOC);
$stmtUsuarios->close();

// Processar criação da partida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_partida'])) {
    $idJogo = intval($_POST['idJogo']);
    $duracao = intval($_POST['duracao']);
    $usuariosSelecionados = isset($_POST['usuarios']) ? $_POST['usuarios'] : [];
    
    // Adicionar o usuário atual à partida
    array_push($usuariosSelecionados, $idUsuario);
    
    // Inserir partida
    $sqlPartida = "INSERT INTO Partida (idJogo, dataPartida, duracaoMinutos) VALUES (?, NOW(), ?)";
    $stmtPartida = $conn->prepare($sqlPartida);
    $stmtPartida->bind_param("ii", $idJogo, $duracao);
    
    if ($stmtPartida->execute()) {
        $idPartida = $conn->insert_id;
        
        // Inserir participantes da partida
        $sqlParticipante = "INSERT INTO PartidaUsuario (idPartida, idUsuario, resultado, tempoJogadoMinutos) VALUES (?, ?, ?, ?)";
        $stmtParticipante = $conn->prepare($sqlParticipante);
        
        foreach ($usuariosSelecionados as $idUsuarioPartida) {
            // Definir resultado: 1 = vitória, 0 = derrota (inicialmente todos como derrota)
            $resultado = 0;
            $tempoJogado = $duracao;
            
            $stmtParticipante->bind_param("iiii", $idPartida, $idUsuarioPartida, $resultado, $tempoJogado);
            $stmtParticipante->execute();
        }
        $stmtParticipante->close();
        
        $_SESSION['mensagem'] = "Partida criada com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso";
        $_SESSION['idPartidaCriada'] = $idPartida;
        
        header("Location: partida.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao criar partida: " . $conn->error;
        $_SESSION['tipo_mensagem'] = "erro";
    }
    $stmtPartida->close();
}

// Processar definição do vencedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['definir_vencedor'])) {
    $idPartida = intval($_POST['idPartida']);
    $vencedores = isset($_POST['idVencedor']) ? $_POST['idVencedor'] : [];
    
    // Se não for array, converte para array (para compatibilidade)
    if (!is_array($vencedores)) {
        $vencedores = [$vencedores];
    }
    
    // Atualizar todos os participantes para derrota (resultado = 0)
    $sqlAtualizarDerrotas = "UPDATE PartidaUsuario SET resultado = 0 WHERE idPartida = ?";
    $stmtDerrotas = $conn->prepare($sqlAtualizarDerrotas);
    $stmtDerrotas->bind_param("i", $idPartida);
    $stmtDerrotas->execute();
    $stmtDerrotas->close();
    
    // Definir os vencedores (resultado = 1)
    if (!empty($vencedores)) {
        $sqlAtualizarVitoria = "UPDATE PartidaUsuario SET resultado = 1 WHERE idPartida = ? AND idUsuario = ?";
        $stmtVitoria = $conn->prepare($sqlAtualizarVitoria);
        
        foreach ($vencedores as $idVencedor) {
            $idVencedor = intval($idVencedor);
            $stmtVitoria->bind_param("ii", $idPartida, $idVencedor);
            $stmtVitoria->execute();
        }
        $stmtVitoria->close();
        
        // Atualizar estatísticas dos usuários
        atualizarEstatisticas($conn, $idPartida);
        
        $_SESSION['mensagem'] = "Vencedor(es) definido(s) com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Selecione pelo menos um vencedor.";
        $_SESSION['tipo_mensagem'] = "erro";
    }
    
    header("Location: partida.php");
    exit();
}

// Buscar partidas do usuário
$sqlPartidas = "SELECT p.*, j.nomeJogo, 
                (SELECT COUNT(*) FROM PartidaUsuario pu WHERE pu.idPartida = p.idPartida) as totalJogadores,
                (SELECT GROUP_CONCAT(u.nomeUsuario SEPARATOR ', ') 
                 FROM Usuario u 
                 JOIN PartidaUsuario pu ON u.idUsuario = pu.idUsuario 
                 WHERE pu.idPartida = p.idPartida AND pu.resultado = 1) as vencedores
                FROM Partida p 
                JOIN Jogo j ON p.idJogo = j.idJogo 
                WHERE p.idPartida IN (SELECT idPartida FROM PartidaUsuario WHERE idUsuario = ?)
                ORDER BY p.dataPartida DESC";
$stmtPartidas = $conn->prepare($sqlPartidas);
$stmtPartidas->bind_param("i", $idUsuario);
$stmtPartidas->execute();
$resultPartidas = $stmtPartidas->get_result();
$partidas = $resultPartidas->fetch_all(MYSQLI_ASSOC);
$stmtPartidas->close();

// Função para atualizar estatísticas
function atualizarEstatisticas($conn, $idPartida) {
    // Buscar informações da partida
    $sqlPartida = "SELECT * FROM Partida WHERE idPartida = ?";
    $stmtPartida = $conn->prepare($sqlPartida);
    $stmtPartida->bind_param("i", $idPartida);
    $stmtPartida->execute();
    $partida = $stmtPartida->get_result()->fetch_assoc();
    $stmtPartida->close();
    
    // Buscar participantes
    $sqlParticipantes = "SELECT * FROM PartidaUsuario WHERE idPartida = ?";
    $stmtParticipantes = $conn->prepare($sqlParticipantes);
    $stmtParticipantes->bind_param("i", $idPartida);
    $stmtParticipantes->execute();
    $participantes = $stmtParticipantes->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtParticipantes->close();
    
    foreach ($participantes as $participante) {
        // Verificar se já existe estatística para o usuário
        $sqlVerificar = "SELECT * FROM EstatisticaUsuario WHERE idUsuario = ?";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->bind_param("i", $participante['idUsuario']);
        $stmtVerificar->execute();
        $resultVerificar = $stmtVerificar->get_result();
        
        if ($resultVerificar->num_rows > 0) {
            // Atualizar estatística existente
            $estatistica = $resultVerificar->fetch_assoc();
            $totalPartidas = $estatistica['totalPartidas'] + 1;
            $totalVitorias = $estatistica['totalVitorias'] + $participante['resultado'];
            $totalHoras = $estatistica['totalHorasJogadas'] + ($participante['tempoJogadoMinutos'] / 60);
            
            $sqlAtualizar = "UPDATE EstatisticaUsuario SET 
                            totalPartidas = ?, 
                            totalVitorias = ?, 
                            totalHorasJogadas = ? 
                            WHERE idUsuario = ?";
            $stmtAtualizar = $conn->prepare($sqlAtualizar);
            $stmtAtualizar->bind_param("iiii", $totalPartidas, $totalVitorias, $totalHoras, $participante['idUsuario']);
            $stmtAtualizar->execute();
            $stmtAtualizar->close();
        } else {
            // Inserir nova estatística
            $totalVitorias = $participante['resultado'];
            $totalHoras = $participante['tempoJogadoMinutos'] / 60;
            
            $sqlInserir = "INSERT INTO EstatisticaUsuario (idUsuario, totalPartidas, totalVitorias, totalHorasJogadas) 
                          VALUES (?, 1, ?, ?)";
            $stmtInserir = $conn->prepare($sqlInserir);
            $stmtInserir->bind_param("iii", $participante['idUsuario'], $totalVitorias, $totalHoras);
            $stmtInserir->execute();
            $stmtInserir->close();
        }
        $stmtVerificar->close();
    }
}
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Partida - GameCut</title>
    <link rel="stylesheet" href="style/jogos.css">
    <link rel="stylesheet" href="style/partida.css">
</head>
<body class="bg-animado">

<header>
    <div id="fundologo">
        <img class="logo" src="source/logoRoxa-Remove.png" alt="">
    </div>
    <div id="fundoButtons">
        <div class="botoesHeader">
            <a class="a" href="index.php">home</a>
            <a class="a" href="jogos.php">Jogos</a>
            <a class="a" href="biblioteca.php">Biblioteca</a>
            <a class="a" href="partida.php">Partida</a>
        </div>
        <div class="perfilLogin">
            <?php if (isset($_SESSION['usuario'])): ?>
            <div class="dropdown">
                <button class="dropbtn"><?= htmlspecialchars($nomeUsuario) ?></button>
                <div class="dropdown-content">
                    <a class="a2" href="perfil.php">Perfil</a>
                    <a class="a2" href="logout.php">Sair</a>
                </div>
            </div>
            <div class="fotoPerfil">
                <img class="fotinhaPerfil" src="source/gatopewpew.jpg" alt="">
            </div>
            <?php else: ?>
            <a href="login.php">login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<section class="partida-container">
    <p class="tituloConteudo">Criar Partida</p>
    
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem <?= $_SESSION['tipo_mensagem'] ?>">
            <?= $_SESSION['mensagem'] ?>
        </div>
        <?php unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']); ?>
    <?php endif; ?>
    
    <div class="criar-partida">
        <form method="POST">
            <div class="form-group">
                <label for="idJogo">Selecione o Jogo:</label>
                <select id="idJogo" name="idJogo" required>
                    <option value="">-- Selecione um jogo --</option>
                    <?php foreach ($jogos as $jogo): ?>
                        <option value="<?= $jogo['idJogo'] ?>"><?= htmlspecialchars($jogo['nomeJogo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="duracao">Duração da Partida (minutos):</label>
                <input type="number" id="duracao" name="duracao" min="1" max="480" value="30" required>
            </div>
            
            <div class="form-group">
                <label>Selecione os Participantes:</label>
                <div class="usuarios-container">
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <div class="usuario-checkbox">
                                <input type="checkbox" name="usuarios[]" value="<?= $usuario['idUsuario'] ?>" id="usuario_<?= $usuario['idUsuario'] ?>">
                                <label for="usuario_<?= $usuario['idUsuario'] ?>"><?= htmlspecialchars($usuario['nomeUsuario']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: white;">Nenhum outro usuário disponível.</p>
                    <?php endif; ?>
                </div>
                <small style="color: #fdc200;">Você será automaticamente adicionado como participante.</small>
            </div>
            
            <button type="submit" name="criar_partida" class="btn-criar">Criar Partida</button>
        </form>
    </div>
    
    <div class="minhas-partidas">
        <h2 style="color: white; font-family: 'Bebas Neue', sans-serif; margin-bottom: 20px;">Minhas Partidas</h2>
        
        <?php if (count($partidas) > 0): ?>
            <?php foreach ($partidas as $partida): ?>
                <div class="partida-item">
                  <div class="partida-info">
    <strong>Jogo:</strong> <?= htmlspecialchars($partida['nomeJogo']) ?><br>
    <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($partida['dataPartida'])) ?><br>
    <strong>Duração:</strong> <?= $partida['duracaoMinutos'] ?> minutos<br>
    <strong>Jogadores:</strong> <?= $partida['totalJogadores'] ?><br>
    <?php if ($partida['vencedores']): ?>
        <strong class="vencedor-info">Vencedor(es): 
            <?php 
            $vencedoresArray = explode(', ', $partida['vencedores']);
            foreach ($vencedoresArray as $index => $vencedor) {
                if ($index > 0) echo ', ';
                echo '<span class="nome-vencedor">' . htmlspecialchars($vencedor) . '</span>';
            }
            ?>
        </strong>
    <?php else: ?>
        <strong style="color: #ff4444;">Partida sem vencedor definido</strong>
    <?php endif; ?>
</div>
                    
                    <?php if (!$partida['vencedores']): ?>
                    <div class="partida-acoes">
                        <form method="POST" class="vencedor-form">
                            <input type="hidden" name="idPartida" value="<?= $partida['idPartida'] ?>">
                            <div class="vencedores-container">
                                <label>Selecione o(s) vencedor(es):</label>
                                <div class="vencedores-checkboxes">
                                    <?php 
                                    // Buscar participantes desta partida
                                    $sqlParticipantes = "SELECT u.idUsuario, u.nomeUsuario 
                                                       FROM PartidaUsuario pu 
                                                       JOIN Usuario u ON pu.idUsuario = u.idUsuario 
                                                       WHERE pu.idPartida = ?";
                                    $stmtParticipantes = $conn->prepare($sqlParticipantes);
                                    $stmtParticipantes->bind_param("i", $partida['idPartida']);
                                    $stmtParticipantes->execute();
                                    $participantes = $stmtParticipantes->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $stmtParticipantes->close();
                                    
                                    foreach ($participantes as $participante): ?>
                                        <div class="vencedor-checkbox">
                                            <input type="checkbox" name="idVencedor[]" value="<?= $participante['idUsuario'] ?>" 
                                                   id="vencedor_<?= $partida['idPartida'] ?>_<?= $participante['idUsuario'] ?>">
                                            <label for="vencedor_<?= $partida['idPartida'] ?>_<?= $participante['idUsuario'] ?>">
                                                <?= htmlspecialchars($participante['nomeUsuario']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button type="submit" name="definir_vencedor" class="btn-vencedor">Definir Vencedor(es)</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white; text-align: center;">Você ainda não participou de nenhuma partida.</p>
        <?php endif; ?>
    </div>
</section>

<script>
    // Transição de background
    setTimeout(() => {
        document.body.classList.remove('bg-animado');
        document.body.classList.add('bg-estatico');
    }, 10000);
</script>

</body>
</html>