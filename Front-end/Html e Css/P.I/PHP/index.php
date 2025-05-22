<?php
session_start();

// Conexão com o banco de dados
$conexao = new mysqli('localhost', 'root', '', 'ControleFinanceiro');

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Processa o formulário de login
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conexao->real_escape_string(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    // Corrigir os nomes das colunas da tabela
    $sql = "SELECT idUsuario AS id, nomeUsuario AS nome, emailUsuario AS email, senhaUsuario AS senha 
            FROM Usuarios WHERE emailUsuario = ?";
    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        $erro = "Erro no sistema. Tente novamente mais tarde.";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verifica a senha com MD5
            if (md5($senha) === $usuario['senha']) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['mensagem'] = "Login realizado com sucesso!";
                header('Location: home.php');
                exit();
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.CSS">
    <title>Login</title>
</head>

<body>
    <section>
        <form action="" method="POST">
            <div class="Logo">
                <img 
                    src="../Source/Logo.png" alt="Logo"
                    style="margin-top: -7px; margin-bottom: 40px; border-radius: 100px; width: 207px; height: 200px;">
            </div>
            <div class="inputBox">
                <input class="inputs" type="text" name="email" placeholder="Email..." required>
                <input class="inputs" type="password" name="senha" placeholder="Senha..." required>
            </div>
            <div class="fundoBotao">
                <button class="botao" type="submit">Entrar</button> <!-- Corrigido para botão -->
            </div>
            <div>
                <p class="textoCadastrar">
                    Ainda não tem conta? <a class="cadastrar" href="registros.php">Cadastre-se</a>
                </p>
            </div>

            <?php if ($erro): ?>
                <div style="color: red; text-align: center;"><?php echo $erro; ?></div>
            <?php endif; ?>
        </form>
    </section>
</body>

</html>
