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
?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GameCut, Home! </title>
    <link rel="stylesheet" href="style/index.css">
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
                    <a class="a" href="">.um</a>
                    <a class="a" href="">.dois</a>
                    <a class="a" href="">.tres</a>
                </div>
                <div class="perfilLogin">
                <?php if (isset($_SESSION['usuario'])): ?>
                <div class="dropdown">
                    <button class="dropbtn"><?= htmlspecialchars($_SESSION['usuario']['nome']) ?></button>
                        <div class="dropdown-content">
                            <a href="perfil.php">Perfil</a>
                            <a href="logout.php">Sair</a>
                        </div>
                </div>
                <div class="fotoPerfil">
                    <img class="fotinhaPerfil" src="source/marcosFOTO.jpg" alt="">
                </div>
                <?php else: ?>
                    <!-- Usuário NÃO logado -->
                    <a href="login.php">login</a>
                <?php endif; ?>
            </div>
        </header>
        <div id="Banner">
            <img class="fotoBan" src="source/banner.gif" alt="">
        </div>

        <section>
            <p class="tituloConteudo">Lançamentos</p>
            <article>
                <div id="Cards">
                    <a href="">
                        <div class="fundoFotoCard">
                            <img class="fotoCard" src="source/gpt.png" alt="">
                        </div>
                        <div class="textoCard">
                            estou aqui
                        </div>
                    </a>
                </div>

                <div id="Cards">
                    <a href="">
                        <div class="fundoFotoCard">
                            <img class="fotoCard" src="source/gpt.png" alt="">
                        </div>
                        <div class="textoCard">
                            estou aqui
                        </div>
                    </a>
                </div>

                <div id="Cards">
                    <a href="">
                        <div class="fundoFotoCard">
                            <img class="fotoCard" src="source/gpt.png" alt="">
                        </div>
                        <div class="textoCard">
                            estou aqui
                        </div>
                    </a>
                </div>

                <div id="Cards">
                    <a href="">
                        <div class="fundoFotoCard">
                            <img class="fotoCard" src="source/gpt.png" alt="">
                        </div>
                        <div class="textoCard">
                            estou aqui
                        </div>
                    </a>
                </div>
            </article>
        </section>
</body>
<script>
setTimeout(() => {
  document.body.classList.remove('bg-animado');
  document.body.classList.add('bg-estatico');
}, 14500);

</script>
</html>
<?php
$conn->close(); // Fechar conexão
?>
