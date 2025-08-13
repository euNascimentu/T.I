<?php
session_start(); // Sempre iniciar a sessão antes de qualquer HTML

// ===== Conexão com o banco =====
$host = "localhost";      // Servidor do banco
$usuario = "root";        // Usuário MySQL
$senha = "";              // Senha MySQL (troque se tiver)
$banco = "PIBD";          // Nome do banco

$conn = new mysqli($host, $usuario, $senha, $banco);

// Testar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8"); // Evitar problemas com acentos

// ===== Buscar todos os usuários =====
$sql = "SELECT * FROM Usuario";
$result = $conn->query($sql);

$usuarios = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

/* RAWG API */

// Sua chave de API (substitua pelo valor correto)
$apiKey = "2bf7427a54a148aa9674a33abf59fa0a";

// URL da API RAWG
$url = "https://api.rawg.io/api/games?key={$apiKey}&dates=2019-09-01,2019-09-30&platforms=18,1,7";

// Fazendo a requisição
$response = file_get_contents($url);

// Convertendo JSON para array associativo
$data = json_decode($response, true);

// Pegando o primeiro jogo
$primeiroJogo = $data["results"][0];
$gameName = $primeiroJogo["name"];
$gameImage = $primeiroJogo["background_image"];
$releaseDate = $primeiroJogo["released"];

?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GameCut, Home! </title>
    <link rel="stylesheet" href="style/jogos.css">
    <style>
        /*Press start 2p*/
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');
        /*Bungee*/
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
        <section>
            <p class="tituloConteudo">Jogos</p>
            <article>
        <?php if (!empty($data["results"])): ?>
            <?php foreach ($data["results"] as $jogo): ?>
                <div id="Cards">
                    <a href="#">
                        <div class="fundoFotoCard">
                            <img class="fotoCard" src="<?= htmlspecialchars($jogo["background_image"]) ?>" alt="<?= htmlspecialchars($jogo["name"]) ?>">
                        </div>
                        <div class="textoCard">
                            <?= htmlspecialchars($jogo["name"]) ?>
                        </div>
                        <div class="categoria">
                            <?= htmlspecialchars($jogo["genres"]) ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum jogo encontrado na API.</p>
        <?php endif; ?>
    </article>
        </section>
</body>
<script>
setTimeout(() => {
  document.body.classList.remove('bg-animado');
  document.body.classList.add('bg-estatico');
}, 10000);

</script>
</html>