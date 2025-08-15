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

/* RAWG API */
$apiKey = "2bf7427a54a148aa9674a33abf59fa0a";
$atualizarAposHoras = 24; // cache válido por 24 horas

// Buscar todos os jogos
$sql = "SELECT * FROM Jogo";
$result = $conn->query($sql);
$jogos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $precisaAtualizar = true;

        if (!empty($row['ultimaAtualizacaoApi'])) {
            $ultimaAtualizacao = strtotime($row['ultimaAtualizacaoApi']);
            $agora = time();

            if (($agora - $ultimaAtualizacao) < ($atualizarAposHoras * 3600)) {
                $precisaAtualizar = false; // dados ainda válidos
            }
        }

        // Só chama API se precisar
        if ($precisaAtualizar) {
            $nomeJogo = urlencode($row['nomeJogo']);
            $url = "https://api.rawg.io/api/games?key={$apiKey}&search={$nomeJogo}";
            $response = @file_get_contents($url);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (!empty($data["results"])) {
                    $apiGame = $data["results"][0];

                    // Variáveis para bind_param
                    $imagemApi = $apiGame["background_image"] ?? '';
                    $notaMediaApi = $apiGame["rating"] ?? 0;
                    $generoApi = !empty($apiGame["genres"]) ? $apiGame["genres"][0]["name"] : $row['generoJogo'];
                    $descricaoApi = $apiGame["short_screenshots"][0]["image"] ?? '';
                    $idJogo = $row['idJogo'];

                    // Atualiza no banco
                    $stmt = $conn->prepare("UPDATE Jogo 
                        SET imagemApi=?, notaMediaApi=?, generoApi=?, descricaoApi=?, ultimaAtualizacaoApi=NOW()
                        WHERE idJogo=?");
                    $stmt->bind_param("sdssi", $imagemApi, $notaMediaApi, $generoApi, $descricaoApi, $idJogo);
                    $stmt->execute();
                    $stmt->close();

                    // Atualiza variável local
                    $row['imagemApi'] = $imagemApi;
                    $row['notaMediaApi'] = $notaMediaApi;
                    $row['generoApi'] = $generoApi;
                    $row['descricaoApi'] = $descricaoApi;
                }
            }
        }

        // Preenche fallback caso algo falhe
        $row['imagemApi'] = $row['imagemApi'] ?? 'source/sem-imagem.jpg';
        $row['notaMediaApi'] = $row['notaMediaApi'] ?? 'N/A';
        $row['generoApi'] = $row['generoApi'] ?? $row['generoJogo'];
        $row['descricaoApi'] = $row['descricaoApi'] ?? '';

        $jogos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameCut, Home!</title>
    <link rel="stylesheet" href="style/jogos.css">
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
            <a href="login.php">login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<section>
    <p class="tituloConteudo">Jogos</p>
    <article class="l-container">
<?php foreach ($jogos as $jogo): ?>
    <div class="b-game-card">
        <div class="b-game-card__cover" style="background-image: url('<?= htmlspecialchars($jogo['imagemApi']) ?>');">
            <div class="b-game-card__hover">
                <h3 class="b-game-card__title"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                <p class="b-game-card__genre"><?= htmlspecialchars($jogo['generoApi']) ?></p>
                <p class="b-game-card__rating">⭐ <?= htmlspecialchars($jogo['notaMediaApi']) ?></p>
                <?php if(!empty($jogo['descricaoApi'])): ?>
                <p class="b-game-card__desc"><?= htmlspecialchars($jogo['descricaoApi']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</article>
</section>

<script>
setTimeout(() => {
    document.body.classList.remove('bg-animado');
    document.body.classList.add('bg-estatico');
}, 10000);
</script>

</body>
</html>
