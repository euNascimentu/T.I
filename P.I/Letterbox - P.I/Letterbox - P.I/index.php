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
 
// ===== Buscar jogos de 2025 =====
$url_2025 = "https://api.rawg.io/api/games?key={$apiKey}&dates=2025-01-01,2025-12-31&ordering=-added&page_size=5";
$response_2025 = @file_get_contents($url_2025);
$jogos = [];
 
if ($response_2025 !== false) {
    $data = json_decode($response_2025, true);
    if (!empty($data["results"])) {
        foreach ($data["results"] as $apiJogo) {
            // Verificar se o jogo existe no banco
            $nomeJogo = $apiJogo["name"] ?? 'Nome não disponível';
            $sqlVerificar = "SELECT idJogo FROM Jogo WHERE nomeJogo = ?";
            $stmtVerificar = $conn->prepare($sqlVerificar);
            $stmtVerificar->bind_param("s", $nomeJogo);
            $stmtVerificar->execute();
            $resultVerificar = $stmtVerificar->get_result();
            
            $idJogo = null;
            if ($resultVerificar->num_rows > 0) {
                $row = $resultVerificar->fetch_assoc();
                $idJogo = $row['idJogo'];
            }
            $stmtVerificar->close();
            
            $jogo = [
                'idJogo' => $idJogo,
                'nomeJogo' => $nomeJogo,
                'imagem' => $apiJogo["background_image"] ?? 'source/sem-imagem.jpg',
                'genero' => !empty($apiJogo["genres"][0]["name"]) ? $apiJogo["genres"][0]["name"] : 'Gênero não disponível',
                'notaMedia' => $apiJogo["rating"] ?? '0',
                'descricao' => $apiJogo["description_raw"] ?? 'Descrição não disponível',
                'dataLancamento' => $apiJogo["released"] ?? 'Data não disponível',
                'anoLancamento' => '2025'
            ];
            $jogos[] = $jogo;
           
            // Parar quando tivermos 3 jogos
            if (count($jogos) >= 3) {
                break;
            }
        }
    }
}
 
// Se não houver jogos de 2025 suficientes, buscar jogos mais recentes
if (count($jogos) < 3) {
    $url_recentes = "https://api.rawg.io/api/games?key={$apiKey}&ordering=-released&page_size=5";
    $response_recentes = @file_get_contents($url_recentes);
   
    if ($response_recentes !== false) {
        $data = json_decode($response_recentes, true);
        if (!empty($data["results"])) {
            foreach ($data["results"] as $apiJogo) {
                // Pular jogos que já estão na lista
                $jaExiste = false;
                foreach ($jogos as $jogoExistente) {
                    if ($jogoExistente['nomeJogo'] === $apiJogo["name"]) {
                        $jaExiste = true;
                        break;
                    }
                }
               
                if (!$jaExiste) {
                    // Verificar se o jogo existe no banco
                    $nomeJogo = $apiJogo["name"] ?? 'Nome não disponível';
                    $sqlVerificar = "SELECT idJogo FROM Jogo WHERE nomeJogo = ?";
                    $stmtVerificar = $conn->prepare($sqlVerificar);
                    $stmtVerificar->bind_param("s", $nomeJogo);
                    $stmtVerificar->execute();
                    $resultVerificar = $stmtVerificar->get_result();
                    
                    $idJogo = null;
                    if ($resultVerificar->num_rows > 0) {
                        $row = $resultVerificar->fetch_assoc();
                        $idJogo = $row['idJogo'];
                    }
                    $stmtVerificar->close();
                    
                    $jogo = [
                        'idJogo' => $idJogo,
                        'nomeJogo' => $nomeJogo,
                        'imagem' => $apiJogo["background_image"] ?? 'source/sem-imagem.jpg',
                        'genero' => !empty($apiJogo["genres"][0]["name"]) ? $apiJogo["genres"][0]["name"] : 'Gênero não disponível',
                        'notaMedia' => $apiJogo["rating"] ?? '0',
                        'descricao' => $apiJogo["description_raw"] ?? 'Descrição não disponível',
                        'dataLancamento' => $apiJogo["released"] ?? 'Data não disponível',
                        'anoLancamento' => isset($apiJogo["released"]) ? date('Y', strtotime($apiJogo["released"])) : 'Desconhecido'
                    ];
                    $jogos[] = $jogo;
                   
                    // Parar quando tivermos 3 jogos
                    if (count($jogos) >= 3) {
                        break;
                    }
                }
            }
        }
    }
}
 
// Se ainda não houver jogos suficientes, buscar do banco como fallback
if (count($jogos) < 3) {
    $sql = "SELECT * FROM Jogo ORDER BY idJogo DESC LIMIT 3";
    $result = $conn->query($sql);
   
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Buscar informações na API RAWG como fallback
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
                    $row['dataLancamento'] = $apiJogo["released"] ?? '';
                    $row['anoLancamento'] = isset($apiJogo["released"]) ? date('Y', strtotime($apiJogo["released"])) : 'Desconhecido';
                }
            }
            $jogos[] = $row;
        }
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
        /* Estilos anteriores mantidos */
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
       
        /* Estilos para o efeito de digitação */
        .typing-char {
            opacity: 0;
            animation: fadeIn 0.1s forwards;
        }
       
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
       
        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 1em;
            background-color: #27c7a4;
            margin-left: 2px;
            animation: blink 1s infinite;
            vertical-align: middle;
        }
       
        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0;
            }
        }
       
        /* Estilos para a data de lançamento */
        .data-lancamento {
            font-size: 0.9rem;
            color: #a0a0c0;
            margin-top: 5px;
        }
       
        .ano-destaque {
            color: #6b8cff;
            font-weight: bold;
        }
       
        /* Mensagem de aviso */
        .aviso {
            background-color: #2a2a4a;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            color: #a0a0c0;
        }
       
        /* Badge para jogos de 2025 */
        .badge-2025 {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(90deg, #ff6b6b, #ffa86b);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 10;
        }

        /* Estilos para os cards clicáveis */
        .b-game-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .b-game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-animado">
<main>
    
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
                           <a class="a2" href="perfil.php">Perfil</a>
                           <a class="a2" href="logout.php">Sair</a>
                       </div>
               </div>
               <div class="fotoPerfil">
                   <a href="perfil.php">
                       <img class="fotinhaPerfil" src="source/gatopewpew.jpg" alt="">
                   </a>
               </div>
               <?php else: ?>
                   <a href="login.php">login</a>
               <?php endif; ?>
           </div>
       </header>
    
       <div id="Banner">
           <img class="fotoBan" src="source/banner.gif" alt="">
       </div>

       <section id="sectionComunidade">
       <p class="tituloConteudo">Comunidade</p>
    
       <div class="busca-usuario">
           <input type="text" id="buscarUsuario" placeholder="Digite o nome do usuário...">
           <button onclick="buscarUsuarios()">🔍 Buscar</button>
       </div>
    
       <div id="resultadosBusca" class="resultados-busca"></div>
       </section>
    
       <section id="section">
           <p class="tituloConteudo">LANÇAMENTOS<span class="ano-destaque"></span></p>
           <article class="l-container" id="gameCards" style="display:none;" >
           <?php if (empty($jogos)): ?>
               <div class="aviso">
                   <p>Nenhum jogo encontrado. Tente novamente mais tarde.</p>
               </div>
           <?php else: ?>
               <?php foreach ($jogos as $jogo): ?>
               <div class="b-game-card" onclick="window.location.href='jogo_selecionado.php?id=<?= $jogo['idJogo'] ?>'">
                   <?php if (isset($jogo['anoLancamento']) && $jogo['anoLancamento'] == '2025'): ?>
                   <div class="badge-2025">2025</div>
                   <?php endif; ?>
                   <div class="b-game-card__cover" style="background-image: url('<?= htmlspecialchars($jogo['imagem']) ?>');">
                       <div class="b-game-card__hover">
                           <h3 class="b-game-card__title"><?= htmlspecialchars($jogo['nomeJogo']) ?></h3>
                           <p class="b-game-card__genre"><?= htmlspecialchars($jogo['genero']) ?></p>
                           <p class="b-game-card__rating">⭐ <?= htmlspecialchars($jogo['notaMedia']) ?></p>
                           <?php if(!empty($jogo['dataLancamento'])): ?>
                           <p class="data-lancamento">Lançamento: <?= date('d/m/Y', strtotime($jogo['dataLancamento'])) ?></p>
                           <?php endif; ?>
                           <?php if(!empty($jogo['descricao'])): ?>
                           <p class="b-game-card__desc"><?= htmlspecialchars(mb_strimwidth($jogo['descricao'], 0, 150, "...")) ?></p>
                           <?php endif; ?>
                       </div>
                   </div>
               </div>
               <?php endforeach; ?>
           <?php endif; ?>
           </article>
       </section>
    
       <section id="descSite">
           <p class="tituloConteudo"> Quem somos? </p>
    
           <div class="euFoto">
               <div class="fotoDevs">
                   <img id="fernando" src="source/Fernando.png" alt="">
               </div>
    
               <div class="fotoDevs">
                   <img id="pita" src="source/Pedro.png" alt="">
               </div>
    
               <div class="fotoDevs">
                   <img id="filipe" src="source/Filipe.png" alt="">
               </div>
               
               <div class="fotoDevs">
                   <img id="gaby" src="source/Gabi.png" alt="">
               </div>
    
               <div class="fotoDevs">
                   <img id="kauan" src="source/Kauan.png" alt="">
               </div>
    
               <div class="fotoDevs">
                   <img id="joao" src="source/João.png" alt="">
               </div>
           </div>
    
           <div class="bioDevs">
               <p id="bio">
                   Selecione um desenvolvedor para saber mais!
               </p>
           </div>
       </section>
</main>
 
</body>
<script>
    // Dados das biografias
    const bios = {
        fernando: 'Fernando, Web Developer, 20 anos, estudante de Análise e Desenvolvimento de Sistemas. Apaixonado por tecnologia e desenvolvimento web, sempre buscando aprender e inovar. Auxiliar no desenvolvimento do GameCut. Responsável por desenvolver a parte web do GameCut.',
        pita: 'Pedro, Mobile Developer, 19 anos, estudante de Análise e Desenvolvimento de Sistemas. Entusiasta de tecnologia e desenvolvimento mobile, sempre buscando aprender e inovar. Responsável pelo desenvolvimento da parte mobile do GameCut, um site dedicado a jogos e entretenimento digital.',
        filipe: 'Filipe, Full Stack, 19 anos, estudante de Análise e Desenvolvimento de Sistemas no Senac. Cursando ADS na UNIP. Apaixonado por jogos eletrônicos e desenvolvimento mobile e web, sempre em busca de aprender e melhorar. Responsável por desenvolver a aplicação mobile do GameCut, um app focado em jogos e entretenimento digital.',
        kauan: 'Kauan, estudante de análise e desenvolvimento de sistemas. Apaixonado por música, tecnologia, jogos e artes!. Sempre buscando evoluir e adquirir conhecimento. Auxiliar na criação e desenvolvimento do GameCut!',
        gaby: 'Gabrielly, 17 anos, estudante de Análise e Desenvolvimento de Sistemas no Senac. Apaixonada por música e tecnologia, sempre em busca de aprender e evoluir. Responsável pelo desenvolvimento da aplicação desktop do GameCut, um app focado em jogos e entretenimento digital.',
        joao: 'Olá! Meu nome é João Henrique, tenho 18 anos e atualmente estudo no Senac. No meu projeto de API, sou responsável pela parte de desenvolvimento desktop, onde coloco em prática meus conhecimentos em programação. Sou apaixonado por tecnologia e pretendo seguir carreira como desenvolvedor, sempre buscando aprender mais e evoluir na área.'
    };
 
    // Elementos DOM
    var a = window.document.getElementById('fernando')
    var b = window.document.getElementById('pita')
    var c = window.document.getElementById('filipe')
    var d = window.document.getElementById('kauan')
    var e = window.document.getElementById('gaby')
    var f = window.document.getElementById('joao')
    var bio = window.document.getElementById('bio')
 
    // Adicionar event listeners
    a.addEventListener('click', () => typeWriter(bios.fernando));
    b.addEventListener('click', () => typeWriter(bios.pita));
    c.addEventListener('click', () => typeWriter(bios.filipe));
    d.addEventListener('click', () => typeWriter(bios.kauan));
    e.addEventListener('click', () => typeWriter(bios.gaby));
    f.addEventListener('click', () => typeWriter(bios.joao));
 
    // Função para efeito de digitação com fade-in
    function typeWriter(text) {
        // Limpar o conteúdo anterior
        bio.innerHTML = '';
       
        // Dividir o texto em caracteres
        const characters = text.split('');
        let i = 0;
       
        // Adicionar cursor
        const cursor = document.createElement('span');
        cursor.className = 'typing-cursor';
        bio.appendChild(cursor);
       
        // Função para adicionar cada caractere com delay
        function addCharacter() {
            if (i < characters.length) {
                const charSpan = document.createElement('span');
                charSpan.className = 'typing-char';
                charSpan.textContent = characters[i];
                bio.insertBefore(charSpan, cursor);
               
                i++;
                setTimeout(addCharacter, 30); // Velocidade de digitação
            }
        }
       
        // Iniciar o efeito
        addCharacter();
    }
 
    // Simular carregamento com async
    document.addEventListener("DOMContentLoaded", async () => {
        await new Promise(resolve => setTimeout(resolve, 1000)); // simula 1s de delay
        document.getElementById('loader').style.display = 'none';
        document.getElementById('gameCards').style.display = 'flex';
       
        // Troca fundo animado para estático
        setTimeout(() => {
            document.body.classList.remove('bg-animado');
            document.body.classList.add('bg-estatico');
        }, 10000);
    });

    function buscarUsuarios() {
    const termo = document.getElementById('buscarUsuario').value.trim();
    const resultadosDiv = document.getElementById('resultadosBusca');
    
    if (termo === '') {
        resultadosDiv.innerHTML = '<div class="sem-resultados">Digite um nome para buscar</div>';
        return;
    }
    
    resultadosDiv.innerHTML = '<div class="loading">Buscando usuários...</div>';
    
    // Criar requisição AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'buscar_usuarios.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                resultadosDiv.innerHTML = xhr.responseText;
                
                // Adicionar event listeners aos botões após carregar os resultados
                adicionarEventListeners();
            } else {
                resultadosDiv.innerHTML = '<div class="sem-resultados">Erro ao buscar usuários</div>';
            }
        }
    };
    
    xhr.send('termo=' + encodeURIComponent(termo));
}

function adicionarEventListeners() {
    // Adicionar event listener para os botões "Ver Perfil"
    const botoes = document.querySelectorAll('.ver-perfil-btn');
    botoes.forEach(botao => {
        botao.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const userId = this.getAttribute('data-user-id');
            console.log('Clicou no botão, userId:', userId); // Para debug
            
            if (userId) {
                window.location.href = 'perfil_usuario.php?id=' + userId;
            }
        });
    });
    
    // Adicionar event listener para os cards (clicar em qualquer lugar do card)
    const cards = document.querySelectorAll('.usuario-card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Não disparar se clicou no botão
            if (!e.target.classList.contains('ver-perfil-btn')) {
                const userId = this.getAttribute('data-user-id');
                console.log('Clicou no card, userId:', userId); // Para debug
                
                if (userId) {
                    window.location.href = 'perfil_usuario.php?id=' + userId;
                }
            }
        });
    });
}

// Buscar ao pressionar Enter
document.getElementById('buscarUsuario').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarUsuarios();
    }
});

// Buscar automaticamente quando a página carrega (opcional)
document.addEventListener('DOMContentLoaded', function() {
    // Foco no campo de busca
    document.getElementById('buscarUsuario').focus();
});
</script>
</html>