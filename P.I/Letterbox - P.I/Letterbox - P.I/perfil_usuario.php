<?php
session_start();

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

// Verificar se o ID do usuário foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: perfil.php");
    exit();
}

$idUsuarioPerfil = intval($_GET['id']);

// Buscar informações do usuário do perfil
$sqlUsuario = "SELECT * FROM Usuario WHERE idUsuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $idUsuarioPerfil);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();

if ($resultUsuario->num_rows === 0) {
    header("Location: perfil.php");
    exit();
}

$usuarioPerfil = $resultUsuario->fetch_assoc();
$stmtUsuario->close();

// Buscar jogos favoritos do usuário do perfil
$sql_favoritos = "SELECT * FROM Jogo WHERE idJogo IN (SELECT idJogo FROM SalvarJogos WHERE idUsuario = ? AND idTipoSalvar = 2) LIMIT 4";
$stmt_favoritos = $conn->prepare($sql_favoritos);
$stmt_favoritos->bind_param("i", $idUsuarioPerfil);
$stmt_favoritos->execute();
$favoritos = $stmt_favoritos->get_result();

// Buscar jogos planejados do usuário do perfil
$sql_planejados = "SELECT * FROM Jogo WHERE idJogo IN (SELECT idJogo FROM Avaliacao WHERE idUsuario = ?) LIMIT 4";
$stmt_planejados = $conn->prepare($sql_planejados);
$stmt_planejados->bind_param("i", $idUsuarioPerfil);
$stmt_planejados->execute();
$planejados = $stmt_planejados->get_result();

// Verificar se é o próprio perfil
$ehProprioPerfil = isset($_SESSION['usuario']) && $_SESSION['usuario']['idUsuario'] == $idUsuarioPerfil;

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?= htmlspecialchars($usuarioPerfil['nomeUsuario']) ?> - GameCut</title>
    <link rel="stylesheet" href="style/perfil.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

       /* Estilos para os cards de jogo - Título completo no hover */
        .lista-jogos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

       .jogo-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 240px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    display: flex; 
}

        .jogo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(253, 194, 0, 0.5);
        }

        .jogo-imagem {
    width: 100%;
    height: 100%;
    flex-shrink: 0; 
        }

       .jogo-imagem img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    vertical-align: bottom; 
}

        .jogo-titulo {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(37, 24, 49, 0.98), transparent);
            color: #fdc200;
            font-family: 'Bebas Neue', cursive;
            font-size: 20px;
            padding: 70px 15px 15px 15px;
            margin: 0;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            text-align: center;
            white-space: normal;
            overflow: visible;
            height: auto;
            min-height: 80px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            line-height: 1.3;
            word-wrap: break-word;
        }

        .jogo-card:hover .jogo-titulo {
            opacity: 1;
            transform: translateY(0);
        }

        .carregando-imagem {
            color: #999;
            font-style: italic;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #1a1025;
        }
        
        /* Garantir que o texto não seja cortado */
        .jogo-titulo span {
            display: block;
            width: 100%;
            max-height: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        /* Botão voltar para o próprio perfil */
        .voltar-meu-perfil {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #fdc200;
        }

        .btn-voltar {
            background-color: #5827cc;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'Bebas Neue', cursive;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .btn-voltar:hover {
            background-color: #fdc200;
            color: #251831;
            transform: scale(1.05);
        }
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
                    <a class="a" href="jogos.php">Jogos</a>
                    <a class="a" href="biblioteca.php">Biblioteca</a>
                    <a class="a" href="partida.php">partida</a>
                </div>
                <div class="perfilLogin">
                <?php if (isset($_SESSION['usuario'])): ?>
                <div class="dropdown">
                    <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></button>
                        <div class="dropdown-content">
                            <a class="a2" href="perfil.php">Meu Perfil</a>
                            <a class="a2" href="logout.php">Sair</a>
                        </div>
                </div>
                <div class="fotoPerfil">
                    <a href="perfil.php">
                        <img class="fotinhaPerfil" src="source/gatopewpew.jpg" alt="">
                    </a>
                </div>
                <?php else: ?>
                    <!-- Usuário NÃO logado -->
                    <a href="login.php">login</a>
                <?php endif; ?>
            </div>
</header>
<main class="perfil-container">
    <div class="perfil-header">
        <img class="foto-perfil-grande" src="source/gatopewpew.jpg" alt="Foto de perfil">
        <div class="perfil-info">
            <h1 class="perfil-nome"><?= htmlspecialchars($usuarioPerfil['nomeUsuario'] ?? 'Usuário sem nome') ?></h1>
            <p class="perfil-bio"><?= nl2br(htmlspecialchars($usuarioPerfil['bioUsuario'] ?? 'Nenhuma biografia fornecida ainda.')) ?></p>

            <?php if ($ehProprioPerfil): ?>
                <!-- Botão editar - só aparece se for o próprio perfil -->
                <button id="btnEditarPerfil" class="botao-acoes" style="margin-top: 15px;">Editar Perfil</button>
                <a href="partida.php">
                    <button id="btnEditarPerfil" class="botao-acoes" style="margin-top: 15px;">Partidas</button>
                </a>

                <!-- Form editar - só aparece se for o próprio perfil -->
                <form id="formEditarPerfil" action="editar_perfil.php" method="post" style="display:none; margin-top: 15px;">
                    <label for="nomeUsuario">Nome:</label><br>
                    <input type="text" id="nomeUsuario" name="nomeUsuario" value="<?= htmlspecialchars($usuarioPerfil['nomeUsuario'] ?? '') ?>" required><br><br>
                    <label for="bioUsuario">Bio:</label><br>
                    <textarea id="bioUsuario" name="bioUsuario" rows="4" cols="40" required><?= htmlspecialchars($usuarioPerfil['bioUsuario'] ?? '') ?></textarea><br><br>
                    <button type="submit" class="botao-acoes">Salvar</button>
                    <button type="button" id="btnCancelarEdicao" class="botao-acoes cancelar">Cancelar</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Jogos Favoritos -->
    <div class="secao-jogos">
        <h2 class="titulo-secao">Jogos Favoritos</h2>
        <div class="lista-jogos">
            <?php if ($favoritos && $favoritos->num_rows > 0): ?>
                <?php while ($jogo = $favoritos->fetch_assoc()): ?>
                    <a class="jogosFav" onclick="window.location.href='jogo_selecionado.php?id=<?= $jogo['idJogo'] ?>'">
                        <div class="jogo-card" data-nome="<?= htmlspecialchars($jogo['nomeJogo']) ?>">
                            <div class="jogo-imagem">
                                <span class="carregando-imagem">Carregando imagem...</span>
                            </div>
                            <h3 class="jogo-titulo"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="mensagem-vazia">Nenhum jogo favorito adicionado ainda.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Jogos Planejados -->
    <div class="secao-jogos">
        <h2 class="titulo-secao">Jogos Avaliados</h2>
        <div class="lista-jogos">
            <?php if ($planejados && $planejados->num_rows > 0): ?>
                <?php while ($jogo = $planejados->fetch_assoc()): ?>
                    <a class="jogosFav" onclick="window.location.href='jogo_selecionado.php?id=<?= $jogo['idJogo'] ?>'">
                        <div class="jogo-card" data-nome="<?= htmlspecialchars($jogo['nomeJogo']) ?>">
                            <div class="jogo-imagem">
                                <span class="carregando-imagem">Carregando imagem...</span>
                            </div>
                            <h3 class="jogo-titulo"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="mensagem-vazia">Nenhum jogo avaliado ainda.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($ehProprioPerfil): ?>
    <div class="voltar-meu-perfil">
        <a href="perfil.php" class="btn-voltar">← Voltar para meu perfil</a>
    </div>
    <?php endif; ?>
</main>

<script>
// Editar perfil - só funciona se for o próprio perfil
<?php if ($ehProprioPerfil): ?>
document.getElementById('btnEditarPerfil').addEventListener('click', () => {
    document.getElementById('formEditarPerfil').style.display = 'block';
    document.getElementById('btnEditarPerfil').style.display = 'none';
});
document.getElementById('btnCancelarEdicao').addEventListener('click', () => {
    document.getElementById('formEditarPerfil').style.display = 'none';
    document.getElementById('btnEditarPerfil').style.display = 'inline-block';
});
<?php endif; ?>

// Carregar imagens dos jogos da API RAWG
document.addEventListener('DOMContentLoaded', () => {
    const apiKey = '2bf7427a54a148aa9674a33abf59fa0a';
    const jogosCards = document.querySelectorAll('.jogo-card');
    
    jogosCards.forEach(card => {
        const nomeJogo = card.dataset.nome;
        
        // Buscar jogo na API RAWG
        fetch(`https://api.rawg.io/api/games?search=${encodeURIComponent(nomeJogo)}&key=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    // Usar o primeiro resultado (mais relevante)
                    const jogoData = data.results[0];
                    const imagemUrl = jogoData.background_image;
                    
                    // Atualizar a imagem no card
                    const imagemContainer = card.querySelector('.jogo-imagem');
                    if (imagemUrl) {
                        imagemContainer.innerHTML = `<img src="${imagemUrl}" alt="${nomeJogo}">`;
                    } else {
                        imagemContainer.innerHTML = '<span>Imagem não disponível</span>';
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao buscar imagem:', error);
                const imagemContainer = card.querySelector('.jogo-imagem');
                imagemContainer.innerHTML = '<span>Erro ao carregar imagem</span>';
            });
    });
});

setTimeout(() => {
    document.body.style.backgroundColor = '#251831';
}, 2000);
</script>

</body>
</html>