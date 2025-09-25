<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// API RAWG
$apiKey = "2bf7427a54a148aa9674a33abf59fa0a";

// Obter ID do usuário logado
if (isset($_SESSION['usuario']['idUsuario'])) {
    $idUsuario = $_SESSION['usuario']['idUsuario'];
} elseif (isset($_SESSION['usuario']['id'])) {
    $idUsuario = $_SESSION['usuario']['id'];
} else {
    header("Location: login.php");
    exit();
}

// Tipos de salvamento
$tiposSalvamento = [
    1 => "Quero Jogar",
    2 => "Favoritos",
    3 => "Jogando",
    4 => "Completado"
];

// Obter jogos salvos pelo usuário
$jogosSalvos = [];

$sql = "SELECT s.idTipoSalvar, j.* 
        FROM SalvarJogos s 
        INNER JOIN Jogo j ON s.idJogo = j.idJogo 
        WHERE s.idUsuario = ? 
        ORDER BY s.idTipoSalvar";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tipo = $row['idTipoSalvar'];
        if (!isset($jogosSalvos[$tipo])) {
            $jogosSalvos[$tipo] = [];
        }
        
        // Buscar informações na API RAWG
        $nomeJogo = urlencode($row['nomeJogo']);
        $url = "https://api.rawg.io/api/games?key={$apiKey}&search={$nomeJogo}";
        $response = @file_get_contents($url);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            if (!empty($data["results"])) {
                $apiJogo = $data["results"][0];
                $row['imagem'] = $apiJogo["background_image"] ?? 'source/sem-imagem.jpg';
                $row['genero'] = $apiJogo["genres"][0]["name"] ?? $row['generoJogo'] ?? 'Desconhecido';
                $row['notaMedia'] = $apiJogo["rating"] ?? '0';
            } else {
                $row['imagem'] = 'source/sem-imagem.jpg';
                $row['genero'] = $row['generoJogo'] ?? 'Desconhecido';
                $row['notaMedia'] = '0';
            }
        } else {
            $row['imagem'] = 'source/sem-imagem.jpg';
            $row['genero'] = $row['generoJogo'] ?? 'Desconhecido';
            $row['notaMedia'] = '0';
        }
        
        $jogosSalvos[$tipo][] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameCut - Minha Biblioteca</title>
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/biblioteca.css">
</head>
<body class="bg-estatico">
    <header>
        <div id="fundologo">
            <img class="logo" src="source/logoRoxa-Remove.png" alt="GameCut Logo">
        </div>
        <div id="fundoButtons">
            <div class="botoesHeader">
                <a class="a" href="index.php">Home</a>
                <a class="a" href="jogos.php">Jogos</a>
                <a class="a" href="partida.php">partida</a>
            </div>
            <div class="perfilLogin">
                <div class="dropdown">
                    <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario']['nomeUsuario'] ?? $_SESSION['usuario']['nome'] ?? 'Usuário') ?></button>
                    <div class="dropdown-content">
                        <a class="a2" href="perfil.php">Perfil</a>
                        <a class="a2" href="logout.php">Sair</a>
                    </div>
                </div>
                <div class="fotoPerfil">
                    <img class="fotinhaPerfil" src="source/gatopewpew.jpg" alt="Foto de perfil">
                </div>
            </div>
        </div>
    </header>

    <div class="biblioteca-container">
        <h1 class="biblioteca-titulo">Minha Biblioteca</h1>
        
        <!-- Filtros e busca -->
        <div class="biblioteca-filtros">
            <div class="filtro-grupo">
                <span class="filtro-label">Ordenar por:</span>
                <select class="filtro-select" id="ordenar-jogos">
                    <option value="recentes">Mais Recentes</option>
                    <option value="antigos">Mais Antigos</option>
                    <option value="nota">Melhor Avaliados</option>
                    <option value="alfabeto">Ordem Alfabética</option>
                </select>
            </div>
            
            <div class="biblioteca-busca">
                <input type="text" class="busca-input" placeholder="Buscar na biblioteca..." id="busca-jogos">
                <button class="busca-btn">Buscar</button>
            </div>
        </div>
        
        <?php foreach ($tiposSalvamento as $idTipo => $nomeTipo): ?>
            <div class="categoria">
                <h2 class="categoria-titulo"><?= $nomeTipo ?></h2>
                
                <?php if (isset($jogosSalvos[$idTipo]) && count($jogosSalvos[$idTipo]) > 0): ?>
                    <div class="jogos-grid">
                        <?php foreach ($jogosSalvos[$idTipo] as $jogo): ?>
                            <div class="jogo-card" data-jogo-id="<?= $jogo['idJogo'] ?>" data-tipo-salvar="<?= $idTipo ?>">
                                <div class="jogo-imagem-container">
                                    <img src="<?= htmlspecialchars($jogo['imagem']) ?>" alt="<?= htmlspecialchars($jogo['nomeJogo']) ?>" class="jogo-imagem">
                                    <div class="jogo-overlay">
                                          <div class="jogo-acoes">
                                                 <button class="jogo-acao-btn" title="Favorito">❤️</button>
                                                      <button class="jogo-acao-btn" title="Quero Jogar">👁️</button>
                                                          <button class="jogo-acao-btn" title="Jogando">🎮</button>
                                                       <button class="jogo-acao-btn" title="Completado">✅</button>
                                                  <button class="jogo-acao-btn" title="Remover">➖</button>
                                          </div>
                                    </div>
                                    <span class="jogo-categoria"><?= $nomeTipo ?></span>
                                </div>
                                <div class="jogo-info">
                                    <h3 class="jogo-titulo"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                                    <p class="jogo-genero"><?= htmlspecialchars($jogo['genero']) ?></p>
                                    <p class="jogo-nota">⭐ <?= htmlspecialchars($jogo['notaMedia']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="vazio">Nenhum jogo nesta categoria. Adicione jogos à sua biblioteca!</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Funcionalidade básica de busca e filtro
        document.addEventListener('DOMContentLoaded', function() {
            const buscaInput = document.getElementById('busca-jogos');
            const ordenarSelect = document.getElementById('ordenar-jogos');
            const jogosCards = document.querySelectorAll('.jogo-card');
            
            // Busca
            if (buscaInput) {
                buscaInput.addEventListener('input', function() {
                    const termo = this.value.toLowerCase();
                    
                    jogosCards.forEach(card => {
                        const titulo = card.querySelector('.jogo-titulo').textContent.toLowerCase();
                        const genero = card.querySelector('.jogo-genero').textContent.toLowerCase();
                        
                        if (titulo.includes(termo) || genero.includes(termo)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
            
            // Ordenação
            if (ordenarSelect) {
                ordenarSelect.addEventListener('change', function() {
                    alert('Funcionalidade de ordenação será implementada em breve!');
                });
            }
            
            // Adicionar event listeners para os botões de ação
            document.querySelectorAll('.jogo-acao-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const card = this.closest('.jogo-card');
        const idJogo = card.dataset.jogoId;
        const tipoAtual = card.dataset.tipoSalvar;
        const acao = this.textContent;
        
        let tipoAcao = '';
        
        switch(acao) {
            case '❤️':
                tipoAcao = 'favorito';
                break;
            case '👁️':
                tipoAcao = 'quero_jogar';
                break;
            case '🎮':
                tipoAcao = 'jogando';
                break;
            case '✅':
                tipoAcao = 'completado';
                break;
            case '➖':
                tipoAcao = 'remover';
                break;
        }
        
        if (tipoAcao && idJogo) {
            gerenciarJogoBiblioteca(idJogo, tipoAcao, tipoAtual);
        }
    });
});
        });

        function gerenciarJogoBiblioteca(idJogo, acao, tipoSalvar) {
            const formData = new FormData();
            formData.append('acao', acao);
            formData.append('idJogo', idJogo);
            formData.append('tipoSalvar', tipoSalvar || 0);
            
            fetch('processa_biblioteca.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recarregar a página para ver as mudanças
                    location.reload();
                } else {
                    alert('Erro: ' + (data.message || 'Não foi possível processar a ação'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar a ação');
            });
        }
    </script>
</body>
</html>