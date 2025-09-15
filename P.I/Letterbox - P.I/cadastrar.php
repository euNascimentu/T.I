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

// ===== Só processa se o formulário for enviado =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
        echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
        exit;
    }

    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; // senha sem criptografia

    // ===== Inserir usuário =====
    $sql = "INSERT INTO Usuario (nomeUsuario, emailUsuario, senhaUsuario, tipoUsuario, bioUsuario) 
            VALUES (?, ?, ?, 1, '')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        // Login instantâneo
        $_SESSION['idUsuario']    = $stmt->insert_id;
        $_SESSION['nomeUsuario']  = $nome;
        $_SESSION['emailUsuario'] = $email;

        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='home.php';</script>";
    } else {
        if ($conn->errno == 1062) {
            echo "<script>alert('Este email já está cadastrado!'); window.history.back();</script>";
        } else {
            echo "Erro: " . $stmt->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - GameCut</title>
    <link rel="stylesheet" href="style/cadastrar.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Press+Start+2P&display=swap');
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

            <form action="" method="post" onsubmit="return validarFormulario()">
                <div class="inputs">
                    <div class="nome">
                        <p>Usuário</p>
                        <input type="text" name="nome" id="nome">
                    </div>
                    <div class="email">
                        <p>EMAIL</p>
                        <input type="email" name="email" id="email">
                    </div>
                    <div class="senha">
                        <p>SENHA</p>
                        <input type="password" name="senha" id="senha">
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
    function validarFormulario() {
        let nome = document.getElementById("nome").value.trim();
        let email = document.getElementById("email").value.trim();
        let senha = document.getElementById("senha").value.trim();

        if (nome === "" || email === "" || senha === "") {
            alert("Preencha todos os campos!");
            return false;
        }

        let regexEmail = /\S+@\S+\.\S+/;
        if (!regexEmail.test(email)) {
            alert("Digite um e-mail válido!");
            return false;
        }

        if (senha.length < 4) {
            alert("A senha deve ter pelo menos 4 caracteres!");
            return false;
        }

        return true;
    }
    </script>
</body>
</html>
