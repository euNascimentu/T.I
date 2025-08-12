<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");

    if (empty($email) || empty($senha)) {
        echo "<script>alert('Preencha todos os campos!'); history.back();</script>";
        exit;
    }

    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "PIBD";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Agora buscando o nome junto com a senha
    $stmt = $conn->prepare("SELECT nomeUsuario, senhaUsuario FROM Usuario WHERE emailUsuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nomeUsuario, $senhaHash);
        $stmt->fetch();

        if ($senha === $senhaHash) {
            // Salva dados do usuário na sessão
            $_SESSION['usuario'] = [
                'nome' => $nomeUsuario,
                'email' => $email
            ];

            // Redireciona para index.php com flag pra alert
            echo "<script>
                alert('Bem-vindo! {$nomeUsuario}, que a diversão esteja contigo!');
                window.location = 'index.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Senha incorreta!'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Email não encontrado!'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GameCut </title>
    <link rel="stylesheet" href="style/login.css">
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
            <header id="headerCentral"> Bem-vindo ao GameCut!</header>
            <form action="login.php" method="post">
    <div class="inputs">
        <div class="email">
            <p>EMAIL</p>
            <input type="text" name="email" required>
        </div>
        <div class="senha">
            <p>SENHA</p>
            <input type="password" name="senha" required>
        </div>
    </div>
    <footer>
        <div class="fundoBotao">
            <button class="button" type="submit" data-text="Awesome">
                <span class="actual-text">&nbsp;Logar!&nbsp;</span>
                <span aria-hidden="true" class="hover-text">&nbsp;Logar!&nbsp;</span>
            </button>
        </div>
        <div class="fundoCadastro">
            <a href="cadastrar.php" class="button2" data-text="Awesome">
                <span class="actual-text">&nbsp;Cadastrar!&nbsp;</span>
                <span aria-hidden="true" class="hover-text">&nbsp;Cadastrar!&nbsp;</span>
            </a>
        </div>
    </footer>
</form>

</section>
</div>
</body>

</html>
