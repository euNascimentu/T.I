<?php 
session_start();

// ===== Conexão com o banco =====
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

/* RAWG API */
$apiKey = "2bf7427a54a148aa9674a33abf59fa0a";
$atualizarAposHoras = 24; // cache válido por 24 horas

// // ===== Buscar 3 jogos mais recentes =====
$sql = "SELECT * FROM Jogo ORDER BY idJogo DESC LIMIT 3";
$result = $conn->query($sql);
$jogos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Buscar imagem e informações na API RAWG
        $nomeJogo = urlencode($row['nomeJogo']);
        $url = "https://api.rawg.io/api/games?key={$apiKey}&search={$nomeJogo}";
        $response = @file_get_contents($url);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            if (!empty($data["results"])) {
                $apiJogo = $data["results"][0];
                $row['imagem'] = $apiJogo["background_image"] ?? 'source/sem-imagem.jpg';
                $row['genero'] = $apiJogo["genres"][0]["name"] ?? $row['genero'] ?? 'Desconhecido';
                $row['notaMedia'] = $apiJogo["rating"] ?? '0';
                $row['descricao'] = $apiJogo["description_raw"] ?? '';
            } else {
                $row['imagem'] = 'source/sem-imagem.jpg';
                $row['genero'] = $row['genero'] ?? 'Desconhecido';
                $row['notaMedia'] = '0';
                $row['descricao'] = '';
            }
        } else {
            $row['imagem'] = 'source/sem-imagem.jpg';
            $row['genero'] = $row['genero'] ?? 'Desconhecido';
            $row['notaMedia'] = '0';
            $row['descricao'] = '';
        }
        
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
    <link rel="stylesheet" href="style/index.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');

        /* Loader */
        #loader {
            position: fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background-color:#251831;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            z-index:9999;
            color:white;
            font-family:"Bebas Neue", sans-serif;
        }

        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #5827cc;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom:20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
    </style>
</head>
<body class="bg-animado">

    <!-- Tela de loading -->
    <div id="loader">
        <div class="spinner"></div>
        <p>Carregando jogos...</p>
    </div>

    <!-- Conteúdo principal -->
    <header>
        <div id="fundologo">
            <img class="logo" src="source/logoRoxa-Remove.png" alt="">
        </div>
        <div id="fundoButtons">
            <div class="botoesHeader">
                <a class="a" href="">home</a>
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
                <a href="login.php">login</a>
            <?php endif; ?>
        </div>
    </header>

    <div id="Banner">
        <img class="fotoBan" src="source/banner.gif" alt="">
    </div>

    <section id="section">
        <p class="tituloConteudo">Lançamentos</p>
        <article class="l-container" id="gameCards" style="display:none;">
        <?php foreach ($jogos as $jogo): ?>
            <div class="b-game-card">
                <div class="b-game-card__cover" style="background-image: url('<?= htmlspecialchars($jogo['imagem']) ?>');">
                    <div class="b-game-card__hover">
                        <h3 class="b-game-card__title"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                        <p class="b-game-card__genre"><?= htmlspecialchars($jogo['genero']) ?></p>
                        <p class="b-game-card__rating">⭐ <?= htmlspecialchars($jogo['notaMedia']) ?></p>
                        <?php if(!empty($jogo['descricao'])): ?>
                        <p class="b-game-card__desc"><?= htmlspecialchars($jogo['descricao']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </article>
    </section>

    <section id="descSite">
        <p class="tituloConteudo"> Quem somos? </p>

        <div class="euFoto">
            <div class="fotoDevs">
                <img id="fernando" src="source/fernando.jpg" alt="">
            </div>

            <div class="fotoDevs">
                <img id="pita" src="source/pita.jpg" alt="">
            </div>

            <div class="fotoDevs">
                <img id="filipe" src="source/filipe.jpg" alt="">
            </div>

            <div class="fotoDevs">
                <img id="gaby" src="source/gaby.jpg" alt="">
            </div>
            
            <div class="fotoDevs">
                <img id="kauan" src="source/kauan.jpg" alt="">
            </div>

            <div class="fotoDevs">
                <img id="joao" src="source/fernando.jpg" alt="">
            </div>
        </div>
            
        <div class="bioDevs">
            <p id="bio">
                Selecione um desenvolvedor para saber mais!
            </p>
        </div>
    </section>

</body>
<script>
    
    var a = window.document.getElementById('fernando')
    var b = window.document.getElementById('pita')
    var c = window.document.getElementById('filipe')
    var d = window.document.getElementById('kauan')
    var e = window.document.getElementById('gaby')
    var f = window.document.getElementById('joao')
    var bio = window.document.getElementById('bio')

    a.addEventListener('click', fernando);
    b.addEventListener('click', pita);
    c.addEventListener('click', filipe);
    d.addEventListener('click', kauan);
    e.addEventListener('click', gaby);
    f.addEventListener('click', joao);

    function fernando(){
        bio.innerText = 'Fernando, Web Developer, 20 anos, estudante de Análise e Desenvolvimento de Sistemas. Apaixonado por tecnologia e desenvolvimento web, sempre buscando aprender e inovar. Auxiliar no desenvolvimento do GameCut. Responsável por desenvolver a parte web do GameCut.';
        }

    function filipe(){
        bio.innerText = 'Filipe, Full Stack, 19 anos, estudante de Análise e Desenvolvimento de Sistemas no Senac. Cursando ADS na UNIP. Apaixonado por jogos eletrônicos e desenvolvimento mobile e web, sempre em busca de aprender e melhorar. Responsável por desenvolver a aplicação mobile do GameCut, um app focado em jogos e entretenimento digital.'
        }    
        
    function kauan(){
        bio.innerText = 'Kauan, estudante de análise e desenvolvimento de sistemas. Apaixonado por música, tecnologia, jogos e artes!. Sempre buscando evoluir e adquirir conhecimento. Auxiliar na criação e desenvolvimento do GameCut!'
        }
        
    function pita(){
        bio.innerText = 'Pedro, Mobile Developer, 19 anos, estudante de Análise e Desenvolvimento de Sistemas. Entusiasta de tecnologia e desenvolvimento mobile, sempre buscando aprender e inovar. Responsável pelo desenvolvimento da parte mobile do GameCut, um site dedicado a jogos e entretenimento digital.'
        }
        
    function gaby(){
        bio.innerText = 'Gabrielly, 17 anos, estudante de Análise e Desenvolvimento de Sistemas no Senac. Apaixonada por música e tecnologia, sempre em busca de aprender e evoluir. Responsável pelo desenvolvimento da aplicação desktop do GameCut, um app focado em jogos e entretenimento digital.'
        }
        
    function joao(){
        bio.innerText = 'JOÃO!'
        }



    // Simular carregamento com async
    document.addEventListener("DOMContentLoaded", async () => {
        // Aqui você pode adicionar um fetch async se precisar, mas já temos PHP carregado
        await new Promise(resolve => setTimeout(resolve, 1000)); // simula 1s de delay
        document.getElementById('loader').style.display = 'none';
        document.getElementById('gameCards').style.display = 'flex';
        
        // Troca fundo animado para estático
        setTimeout(() => {
            document.body.classList.remove('bg-animado');
            document.body.classList.add('bg-estatico');
        }, 10000);
    });
</script>
</html>
