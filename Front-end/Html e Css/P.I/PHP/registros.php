<?php
session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: home.php');
    exit();
}

// Conexão com o banco de dados
$conexao = new mysqli('localhost', 'root', '', 'ControleFinanceiro');
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Mensagem de erro (opcional)
$erro = '';
?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/registro.CSS">
    <title>Registro</title>
</head>
<body>
    <section>
        <form action="valida_registro.php" method="POST">
            <div class="Logo">
                <img src="../Source/Logo.png" alt="Logo"
                    style="margin-top: -7px; 
                    margin-bottom: 40px;
                    border-radius: 100px; 
                    width: 207px; 
                    height: 200px;">
            </div>

            <div class="inputBox">
                <input class="inputs" type="text" name="nome" placeholder="Nome..." required>
                <input class="inputs" type="email" name="email" placeholder="Email..." required>
                <input class="inputs" type="password" name="senha" placeholder="Senha..." required>
            </div>

            <div class="fundoBotao">
                <button class="botao" type="submit">Cadastrar</button>
            </div>

            <!-- Feedbacks -->
            <?php if (isset($_GET['email']) && $_GET['email'] === 'erro') { ?>
                <div class="text-danger" style="text-align: center;">E-mail já cadastrado!</div>
            <?php } ?>

            <?php if (isset($_GET['usuario']) && $_GET['usuario'] === 'sucesso') { ?>
                <script>alert('Usuário cadastrado com sucesso!');</script>
            <?php } elseif (isset($_GET['usuario']) && $_GET['usuario'] === 'erro') { ?>
                <script>alert('Erro ao inserir usuário. Contate o administrador!');</script>
            <?php } ?>
        </form>
    </section>
</body>
</html>
