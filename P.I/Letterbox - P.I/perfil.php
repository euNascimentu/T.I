<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// ID do usuário logado
$idUsuario = $_SESSION['usuario']['idUsuario'];

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Buscar informações do usuário
$sql = "SELECT * FROM Usuario WHERE idUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc(); // aqui teremos os dados reais do usuário


// Buscar jogos favoritos
$sql_favoritos = "SELECT * FROM Jogo WHERE idJogo IN (SELECT idJogo FROM Favorito WHERE idUsuario = ?) LIMIT 4";
$stmt_favoritos = $conn->prepare($sql_favoritos);
$stmt_favoritos->bind_param("i", $idUsuario);
$stmt_favoritos->execute();
$favoritos = $stmt_favoritos->get_result();

// Buscar jogos planejados
$sql_planejados = "SELECT * FROM Jogo WHERE idJogo IN (SELECT idJogo FROM Avaliacao WHERE idUsuario = ?) LIMIT 4";
$stmt_planejados = $conn->prepare($sql_planejados);
$stmt_planejados->bind_param("i", $idUsuario);
$stmt_planejados->execute();
$planejados = $stmt_planejados->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil - GameCut</title>
    <link rel="stylesheet" href="style/perfil.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');

        .botao-acoes {
            background-color: #fdc200;
            color: #251831;
            border: none;
            padding: 10px 18px;
            font-size: 15px;
            font-family: 'Bebas Neue', cursive;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .botao-acoes:hover {
            background-color: #ffc700;
            transform: scale(1.05);
        }

        .botao-acoes.cancelar {
            background-color: #703d85;
            color: #fff;
        }

        .botao-acoes.cancelar:hover {
            background-color: #5827cc;
        }

        #sugestoesJogos {
            background: #fff;
            color: #000;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            z-index: 99;
            width: 250px;
        }

        #sugestoesJogos div {
            padding: 8px;
            cursor: pointer;
        }

        #sugestoesJogos div:hover {
            background-color: #eee;
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
                    <a class="a" href="">Biblioteca</a>
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
                    <!-- Usuário NÃO logado -->
                    <a href="login.php">login</a>
                <?php endif; ?>
            </div>
</header>
<main class="perfil-container">
    <div class="perfil-header">
        <img class="foto-perfil-grande" src="source/gatopewpew.jpg" alt="Foto de perfil">
        <div class="perfil-info">
            <h1 class="perfil-nome"><?= htmlspecialchars($usuario['nomeUsuario'] ?? 'Usuário sem nome') ?></h1>
            <p class="perfil-bio"><?= nl2br(htmlspecialchars($usuario['bioUsuario'] ?? 'Nenhuma biografia fornecida ainda.')) ?></p>

            <!-- Botão editar -->
            <button id="btnEditarPerfil" class="botao-acoes" style="margin-top: 15px;">Editar Perfil</button>

            <!-- Form editar -->
            <form id="formEditarPerfil" action="editar_perfil.php" method="post" style="display:none; margin-top: 15px;">
                <label for="nomeUsuario">Nome:</label><br>
                <input type="text" id="nomeUsuario" name="nomeUsuario" value="<?= htmlspecialchars($usuario['nomeUsuario'] ?? '') ?>" required><br><br>
                <label for="bioUsuario">Bio:</label><br>
                <textarea id="bioUsuario" name="bioUsuario" rows="4" cols="40" required><?= htmlspecialchars($usuario['bioUsuario'] ?? '') ?></textarea><br><br>
                <button type="submit" class="botao-acoes">Salvar</button>
                <button type="button" id="btnCancelarEdicao" class="botao-acoes cancelar">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Jogos Favoritos -->
    <div class="secao-jogos">
        <h2 class="titulo-secao">Jogos Favoritos</h2>
        <div class="lista-jogos">
            <?php if ($favoritos && $favoritos->num_rows > 0): ?>
                <?php while ($jogo = $favoritos->fetch_assoc()): ?>
                    <div class="jogo-card">
                        <div class="jogo-imagem">
                            <span>Imagem</span>
                        </div>
                        <h3 class="jogo-titulo"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                    </div>
                <?php endwhile; ?>
                <?php else: ?>
                    <p class="mensagem-vazia">Nenhum jogo favorito adicionado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Editar perfil
document.getElementById('btnEditarPerfil').addEventListener('click', () => {
    document.getElementById('formEditarPerfil').style.display = 'block';
    document.getElementById('btnEditarPerfil').style.display = 'none';
});
document.getElementById('btnCancelarEdicao').addEventListener('click', () => {
    document.getElementById('formEditarPerfil').style.display = 'none';
    document.getElementById('btnEditarPerfil').style.display = 'inline-block';
});

// Planejo jogar - formulário
document.getElementById('btnAdicionarPlanejar').addEventListener('click', () => {
    document.getElementById('formAdicionarPlanejar').style.display = 'block';
    document.getElementById('btnAdicionarPlanejar').style.display = 'none';
});
document.getElementById('btnCancelarAdicionarPlanejar').addEventListener('click', () => {
    document.getElementById('formAdicionarPlanejar').style.display = 'none';
    document.getElementById('btnAdicionarPlanejar').style.display = 'inline-block';
});

// Autocomplete com RAWG
const input = document.getElementById('buscaJogo');
const sugestoes = document.getElementById('sugestoesJogos');
const idJogo = document.getElementById('idJogoSelecionado');
const nomeJogo = document.getElementById('nomeJogoSelecionado');

input.addEventListener('input', async () => {
    const query = input.value.trim();
    if (query.length < 2) {
        sugestoes.innerHTML = '';
        return;
    }

    const response = await fetch(`https://api.rawg.io/api/games?search=${encodeURIComponent(query)}&page_size=5&key=2bf7427a54a148aa9674a33abf59fa0a`);
    const data = await response.json();

    sugestoes.innerHTML = '';
    if (data.results) {
        data.results.forEach(jogo => {
            const div = document.createElement('div');
            div.textContent = jogo.name;
            div.dataset.id = jogo.id;
            div.dataset.name = jogo.name;
            div.addEventListener('click', () => {
                input.value = jogo.name;
                idJogo.value = jogo.id;
                nomeJogo.value = jogo.name;
                sugestoes.innerHTML = '';
            });
            sugestoes.appendChild(div);
        });
    }
});

document.addEventListener('click', function (e) {
    if (!document.getElementById('formAdicionarPlanejar').contains(e.target)) {
        sugestoes.innerHTML = '';
    }
});
</script>

<script>
setTimeout(() => {
    document.body.style.backgroundColor = '#251831';
}, 2000);
</script>


<script>
const apiKey = '2bf7427a54a148aa9674a33abf59fa0a';

document.querySelectorAll('.jogo-card[data-idrawg]').forEach(async (card) => {
    const jogoId = card.dataset.idrawg;
    if (!jogoId) return;

    try {
        const response = await fetch(`https://api.rawg.io/api/games/${jogoId}?key=${apiKey}`);
        if (!response.ok) throw new Error('Erro na resposta da API');
        const data = await response.json();

        const divImagem = card.querySelector('.jogo-imagem');
        if (data.background_image) {
           divImagem.innerHTML = `<img src="${data.background_image}" alt="${data.name}" class="jogo-img-card">`;

        } else {
            divImagem.innerHTML = '<span>Imagem indisponível</span>';
        }
    } catch (error) {
        console.error('Erro ao carregar imagem do jogo:', error);
    }
});
</script>

</body>
</html>
