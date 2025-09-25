    <?php
    // Processamento do formulário (mesmo arquivo)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conexão com o banco de dados
        $host = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "PIBD";
        $conn = new mysqli($host, $usuario, $senha, $banco);

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");

        // Obter dados do formulário
        $nomeUsuario = $_POST['usuario'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        
        // Verificar se o usuário já existe
        $sqlVerificar = "SELECT idUsuario FROM Usuario WHERE nomeUsuario = ? OR emailUsuario = ?";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ss", $nomeUsuario, $email);
        $stmtVerificar->execute();
        $resultVerificar = $stmtVerificar->get_result();
        
        if ($resultVerificar->num_rows > 0) {
            echo "<script>alert('Usuário ou email já cadastrado!');</script>";
        } else {
            // Inserir usuário (senha sem criptografia)
            $sqlInserir = "INSERT INTO Usuario (nomeUsuario, emailUsuario, senhaUsuario, tipoUsuario) VALUES (?, ?, ?, 0)";
            $stmtInserir = $conn->prepare($sqlInserir);
            $stmtInserir->bind_param("sss", $nomeUsuario, $email, $senha);
            
            if ($stmtInserir->execute()) {
                echo "<script>
                    alert('Usuário cadastrado com sucesso!');
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 1000);
                </script>";
            } else {
                echo "<script>alert('Erro ao cadastrar usuário!');</script>";
            }
            $stmtInserir->close();
        }
        $stmtVerificar->close();
        $conn->close();
    }
    ?>
<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GameCut </title>
    <link rel="stylesheet" href="style/cadastrar.css">
    <style>
        /*Press start 2p*/
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');
        /*Bungee*/
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Bungee&family=Press+Start+2P&display=swap');
    </style>
</head>

<body>
    <div id="container">
        <header id="header">
            <img class="logo" src="source/logoGameCut-Remove.png" alt="Logo">
        </header>
        <section>
            <header id="headerCentral"> GameCut!</header>
            <form id="cadastroForm" method="post" action="cadastrar.php">
                <div class="inputs">
                    <div class="nome">
                        <p>Usuário</p>
                        <input type="text" id="usuario" name="usuario" required>
                    </div>
                    <div class="email">
                        <p>EMAIL</p>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="senha">
                        <p>SENHA</p>
                        <input type="password" id="senha" name="senha" required minlength="6">
                    </div>
                </div>
                <footer>
                    <div class="gifCat">
                        <img class="cat" src="source/gifCadastro.gif" alt="">
                    </div>
                    <div class="fundoCadastro">
                        <button type="submit" class="button2" data-text="Awesome">
                            <span class="actual-text">&nbsp;Finalizar!&nbsp;</span>
                            <span aria-hidden="true" class="hover-text">&nbsp;Finalizar!&nbsp;</span>
                        </button>
                    </div>
                </footer>
            </form>
        </section>
    </div>

    <script>
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            // Obter valores dos campos
            const usuario = document.getElementById('usuario').value.trim();
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value;
            
            // Validações básicas no frontend
            if (usuario === '') {
                alert('Por favor, preencha o usuário');
                event.preventDefault();
                return;
            }
            
            if (email === '' || !email.includes('@')) {
                alert('Por favor, insira um email válido');
                event.preventDefault();
                return;
            }
            
            if (senha.length < 6) {
                alert('A senha deve ter pelo menos 6 caracteres');
                event.preventDefault();
                return;
            }
            
            // Se válido, o formulário será enviado normalmente para o PHP
        });
    </script>
</body>

</html>