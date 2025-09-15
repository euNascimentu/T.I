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

// Verificar se o ID do jogo foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$idJogo = intval($_GET['id']);

// Buscar informações do jogo
$sqlJogo = "SELECT * FROM Jogo WHERE idJogo = ?";
$stmtJogo = $conn->prepare($sqlJogo);
$stmtJogo->bind_param("i", $idJogo);
$stmtJogo->execute();
$resultJogo = $stmtJogo->get_result();

if ($resultJogo->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$jogo = $resultJogo->fetch_assoc();
$stmtJogo->close();

// Buscar avaliações do jogo
$sqlAvaliacoes = "SELECT a.*, u.nomeUsuario, u.fotoUsuario 
                  FROM Avaliacao a 
                  JOIN Usuario u ON a.idUsuario = u.idUsuario 
                  WHERE a.idJogo = ? 
                  ORDER BY a.idAvaliacao DESC";
$stmtAvaliacoes = $conn->prepare($sqlAvaliacoes);
$stmtAvaliacoes->bind_param("i", $idJogo);
$stmtAvaliacoes->execute();
$resultAvaliacoes = $stmtAvaliacoes->get_result();
$avaliacoes = $resultAvaliacoes->fetch_all(MYSQLI_ASSOC);
$stmtAvaliacoes->close();

// Processar ações do usuário (salvar jogo, avaliar, reportar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario'])) {
    $idUsuario = $_SESSION['usuario']['idUsuario'];
    
    if (isset($_POST['acao'])) {
        // Processar ação de salvar jogo
        $acao = $_POST['acao'];
        $idTipo = 0;
        
        // Mapeamento correto dos valores para os IDs
        switch($acao) {
            case 'quero_jogar':
                $idTipo = 1; // 1 => "Quero Jogar"
                break;
            case 'favorito':
                $idTipo = 2; // 2 => "Favoritos"
                break;
            case 'jogando':
                $idTipo = 3; // 3 => "Jogando"
                break;
            case 'completado':
                $idTipo = 4; // 4 => "Completado"
                break;
            case 'remover':
                $idTipo = intval($_POST['idTipo']); // Tipo específico a ser removido
                break;
        }
        
        if ($acao === 'remover') {
            // Remover ação específica
            $sqlRemover = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar = ?";
            $stmtRemover = $conn->prepare($sqlRemover);
            $stmtRemover->bind_param("iii", $idUsuario, $idJogo, $idTipo);
            $stmtRemover->execute();
            $stmtRemover->close();
        } else {
            // Verificar se já existe esta ação para este jogo
            $sqlVerificar = "SELECT * FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar = ?";
            $stmtVerificar = $conn->prepare($sqlVerificar);
            $stmtVerificar->bind_param("iii", $idUsuario, $idJogo, $idTipo);
            $stmtVerificar->execute();
            $resultVerificar = $stmtVerificar->get_result();
            
            if ($resultVerificar->num_rows > 0) {
                // Remover ação existente (toggle)
                $sqlRemover = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar = ?";
                $stmtRemover = $conn->prepare($sqlRemover);
                $stmtRemover->bind_param("iii", $idUsuario, $idJogo, $idTipo);
                $stmtRemover->execute();
                $stmtRemover->close();
            } else {
                // Se for uma das opções mutuamente exclusivas (não favorito), remover as outras primeiro
                if ($idTipo != 2) { // Não é favorito
                    $sqlRemoverOutros = "DELETE FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ? AND idTipoSalvar IN (1, 3, 4)";
                    $stmtRemoverOutros = $conn->prepare($sqlRemoverOutros);
                    $stmtRemoverOutros->bind_param("ii", $idUsuario, $idJogo);
                    $stmtRemoverOutros->execute();
                    $stmtRemoverOutros->close();
                }
                
                // Inserir nova ação
                $sqlInserir = "INSERT INTO SalvarJogos (idUsuario, idJogo, idTipoSalvar) VALUES (?, ?, ?)";
                $stmtInserir = $conn->prepare($sqlInserir);
                $stmtInserir->bind_param("iii", $idUsuario, $idJogo, $idTipo);
                $stmtInserir->execute();
                $stmtInserir->close();
            }
            $stmtVerificar->close();
        }
        
        // Recarregar a página após a ação
        header("Location: jogo_selecionado.php?id=" . $idJogo);
        exit();
    } elseif (isset($_POST['avaliar'])) {
        // Processar avaliação do jogo
        $nota = intval($_POST['nota']);
        $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
        
        // Verificar se o usuário já avaliou este jogo
        $sqlVerificarAvaliacao = "SELECT * FROM Avaliacao WHERE idUsuario = ? AND idJogo = ?";
        $stmtVerificarAvaliacao = $conn->prepare($sqlVerificarAvaliacao);
        $stmtVerificarAvaliacao->bind_param("ii", $idUsuario, $idJogo);
        $stmtVerificarAvaliacao->execute();
        $resultVerificarAvaliacao = $stmtVerificarAvaliacao->get_result();
        
        if ($resultVerificarAvaliacao->num_rows > 0) {
            // Atualizar avaliação existente
            $sqlAtualizarAvaliacao = "UPDATE Avaliacao SET notaAvaliacao = ?, descricaoAvaliacao = ? WHERE idUsuario = ? AND idJogo = ?";
            $stmtAtualizarAvaliacao = $conn->prepare($sqlAtualizarAvaliacao);
            $stmtAtualizarAvaliacao->bind_param("isii", $nota, $comentario, $idUsuario, $idJogo);
            $stmtAtualizarAvaliacao->execute();
            $stmtAtualizarAvaliacao->close();
        } else {
            // Inserir nova avaliação
            $sqlInserirAvaliacao = "INSERT INTO Avaliacao (idUsuario, idJogo, notaAvaliacao, descricaoAvaliacao) VALUES (?, ?, ?, ?)";
            $stmtInserirAvaliacao = $conn->prepare($sqlInserirAvaliacao);
            $stmtInserirAvaliacao->bind_param("iiis", $idUsuario, $idJogo, $nota, $comentario);
            $stmtInserirAvaliacao->execute();
            $stmtInserirAvaliacao->close();
        }
        $stmtVerificarAvaliacao->close();
        
        // Recarregar a página para mostrar a nova avaliação
        header("Location: jogo_selecionado.php?id=" . $idJogo);
        exit();
    } elseif (isset($_POST['reportar'])) {
        // Processar report de avaliação
        $idAvaliacao = intval($_POST['idAvaliacao']);
        $motivo = $_POST['motivo'];
        
        $sqlReport = "INSERT INTO Report (idUsuario, idAvaliacao, motivo) VALUES (?, ?, ?)";
        $stmtReport = $conn->prepare($sqlReport);
        $stmtReport->bind_param("iis", $idUsuario, $idAvaliacao, $motivo);
        $stmtReport->execute();
        $stmtReport->close();
    }
}

// Buscar ações do usuário atual para este jogo (se estiver logado)
$acoesUsuario = [];
if (isset($_SESSION['usuario'])) {
    $idUsuario = $_SESSION['usuario']['idUsuario'];
    $sqlAcoesUsuario = "SELECT idTipoSalvar FROM SalvarJogos WHERE idUsuario = ? AND idJogo = ?";
    $stmtAcoesUsuario = $conn->prepare($sqlAcoesUsuario);
    $stmtAcoesUsuario->bind_param("ii", $idUsuario, $idJogo);
    $stmtAcoesUsuario->execute();
    $resultAcoesUsuario = $stmtAcoesUsuario->get_result();
    
    while ($row = $resultAcoesUsuario->fetch_assoc()) {
        $acoesUsuario[] = $row['idTipoSalvar'];
    }
    $stmtAcoesUsuario->close();
}
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($jogo['nomeJogo']) ?> - GameCut</title>
    <link rel="stylesheet" href="style/jogos.css">
    <link rel="stylesheet" href="style/jogo_selecionado.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bungee&family=Press+Start+2P&display=swap');
    </style>
</head>
<body class="bg-animado">

<header>
    <div id="fundologo">
        <img class="logo" src="source/logoRoxa-Remove.png" alt="">
    </div>
    <div id="fundoButtons">
        <div class="botoesHeader">
            <a class="a" href="index.php">home</a>
            <a class="a" href="biblioteca.php">Biblioteca</a>
        </div>
        <div class="perfilLogin">
            <?php if (isset($_SESSION['usuario'])): ?>
            <div class="dropdown">
                <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></button>
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

<section>
    <div class="tituloSecao">
        <p class="tituloConteudo">Detalhes do Jogo</p>
    </div>
    
    <div class="jogo-detalhes">
        <div class="jogo-cabecalho">
            <div class="jogo-capa">
                <img src="<?= htmlspecialchars($jogo['imagemApi'] ?? 'source/sem-imagem.jpg') ?>" alt="<?= htmlspecialchars($jogo['nomeJogo']) ?>">
            </div>
            
            <div class="jogo-info">
                <h1 class="jogo-titulo"><?= htmlspecialchars($jogo['nomeJogo']) ?></h1>
                
                <div class="jogo-meta">
                    <div class="jogo-nota">
                        <span class="nota-label">Nota Média:</span>
                        <span class="nota-valor">⭐ <?= htmlspecialchars($jogo['notaMediaApi'] ?? 'N/A') ?></span>
                    </div>
                    
                    <div class="jogo-genero">
                        <span class="genero-label">Gênero:</span>
                        <span class="genero-valor"><?= htmlspecialchars($jogo['generoApi'] ?? $jogo['generoJogo']) ?></span>
                    </div>
                    
                    <div class="jogo-desenvolvedora">
                        <span class="dev-label">Desenvolvedora:</span>
                        <span class="dev-valor"><?= htmlspecialchars($jogo['desenvolvedoraJogo']) ?></span>
                    </div>
                </div>
                
                <div class="jogo-acoes">
                    <form method="POST" class="acoes-form">
                        <!-- Botão Favorito (pode ser combinado com outros) -->
                        <button type="submit" name="acao" value="favorito" class="acao-btn <?= (in_array(2, $acoesUsuario)) ? 'ativo' : '' ?>">❤️ Favorito</button>
                        
                        <!-- Botões mutuamente exclusivos -->
                        <button type="submit" name="acao" value="quero_jogar" class="acao-btn <?= (in_array(1, $acoesUsuario)) ? 'ativo' : '' ?>">👁️ Quero Jogar</button>
                        <button type="submit" name="acao" value="jogando" class="acao-btn <?= (in_array(3, $acoesUsuario)) ? 'ativo' : '' ?>">🎮 Jogando</button>
                        <button type="submit" name="acao" value="completado" class="acao-btn <?= (in_array(4, $acoesUsuario)) ? 'ativo' : '' ?>">✅ Completado</button>
                        
                        <!-- Botões de remoção individuais -->
                        <?php if (in_array(2, $acoesUsuario)): ?>
                            <button type="submit" name="acao" value="remover" class="acao-btn remover" onclick="document.getElementById('idTipoRemover').value=2">➖ Remover Favorito</button>
                        <?php endif; ?>
                        
                        <?php if (in_array(1, $acoesUsuario)): ?>
                            <button type="submit" name="acao" value="remover" class="acao-btn remover" onclick="document.getElementById('idTipoRemover').value=1">➖ Remover Quero Jogar</button>
                        <?php endif; ?>
                        
                        <?php if (in_array(3, $acoesUsuario)): ?>
                            <button type="submit" name="acao" value="remover" class="acao-btn remover" onclick="document.getElementById('idTipoRemover').value=3">➖ Remover Jogando</button>
                        <?php endif; ?>
                        
                        <?php if (in_array(4, $acoesUsuario)): ?>
                            <button type="submit" name="acao" value="remover" class="acao-btn remover" onclick="document.getElementById('idTipoRemover').value=4">➖ Remover Completado</button>
                        <?php endif; ?>
                        
                        <input type="hidden" id="idTipoRemover" name="idTipo" value="">
                    </form>
                </div>
            </div>
        </div>
        
        <div class="jogo-descricao">
            <h2>Descrição</h2>
            <p><?= htmlspecialchars($jogo['descricaoJogo']) ?></p>
        </div>
        
        <?php if (isset($_SESSION['usuario'])): ?>
        <div class="avaliar-jogo">
            <h2>Avaliar Jogo</h2>
            <form method="POST">
                <div class="avaliacao-inputs">
                    <div class="nota-input">
                        <label for="nota">Nota (0-5):</label>
                        <input type="number" id="nota" name="nota" min="0" max="5" required>
                    </div>
                    
                    <div class="comentario-input">
                        <label for="comentario">Comentário (opcional):</label>
                        <textarea id="comentario" name="comentario" rows="4"></textarea>
                    </div>
                </div>
                
                <button type="submit" name="avaliar" class="btn-avaliar">Enviar Avaliação</button>
            </form>
        </div>
        <?php else: ?>
        <div class="avaliar-jogo">
            <p><a href="login.php">Faça login</a> para avaliar este jogo.</p>
        </div>
        <?php endif; ?>
        
        <div class="avaliacoes-lista">
            <h2>Avaliações da Comunidade</h2>
            
            <?php if (count($avaliacoes) > 0): ?>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="avaliacao-item">
                    <div class="avaliacao-cabecalho">
                        <div class="avaliacao-usuario">
                            <img src="source/gatopewpew.jpg" alt="<?= htmlspecialchars($avaliacao['nomeUsuario']) ?>" class="avaliacao-avatar">
                            <span class="avaliacao-nome"><?= htmlspecialchars($avaliacao['nomeUsuario']) ?></span>
                        </div>
                        
                        <div class="avaliacao-nota">
                            ⭐ <?= htmlspecialchars($avaliacao['notaAvaliacao']) ?>/5
                        </div>
                    </div>
                    
                    <?php if (!empty($avaliacao['descricaoAvaliacao'])): ?>
                    <div class="avaliacao-comentario">
                        <p><?= htmlspecialchars($avaliacao['descricaoAvaliacao']) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['usuario'])): ?>
                    <div class="avaliacao-acoes">
                        <button class="btn-reportar" onclick="abrirModalReport(<?= $avaliacao['idAvaliacao'] ?>)">Reportar</button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="sem-avaliacoes">Este jogo ainda não possui avaliações.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal para reportar avaliação -->
<div id="modalReport" class="modal">
    <div class="modal-conteudo">
        <span class="fechar">&times;</span>
        <h2>Reportar Avaliação</h2>
        <form method="POST">
            <input type="hidden" id="idAvaliacaoReport" name="idAvaliacao">
            
            <div class="modal-input">
                <label for="motivo">Motivo do Report:</label>
                <textarea id="motivo" name="motivo" rows="4" required></textarea>
            </div>
            
            <button type="submit" name="reportar" class="btn-reportar-confirmar">Enviar Report</button>
        </form>
    </div>
</div>

<script>
// Script para o modal de report
var modal = document.getElementById("modalReport");
var span = document.getElementsByClassName("fechar")[0];

function abrirModalReport(idAvaliacao) {
    document.getElementById("idAvaliacaoReport").value = idAvaliacao;
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Transição de background
setTimeout(() => {
    document.body.classList.remove('bg-animado');
    document.body.classList.add('bg-estatico');
}, 10000);
</script>

</body>
</html>