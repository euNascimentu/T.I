<?php
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === 'sim') {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateShop - Login</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@500&display=swap" rel="stylesheet">
    <style>
        /* Estilos específicos para a página de login */
        .login-container {
            max-width: 500px;
            margin: 144.5px auto;
            padding: 30px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Oswald', sans-serif;
            color: var(--dark);
        }
        
        .login-form .form-group {
            margin-bottom: 20px;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .login-form input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .login-btn:hover {
            background-color: var(--secondary);
        }
        
        .login-links {
            margin-top: 20px;
            text-align: center;
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .login-links a:hover {
            text-decoration: underline;
        }
        
        .erro-login {
            color: #d32f2f;
            background-color: #fde0e0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .text-danger {
            color: #d32f2f;
            text-align: center;
            margin-top: 15px;
        }
        
        .text-success {
            color: #4CAF50;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="login-container">
            <h2 class="login-title">Login</h2>
            
            <form class="login-form" method="POST" action="valida_login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="login-btn">Entrar</button>
                
                <div class="login-links">
                    <p>Ainda não tem conta? <a href="registros.php">Cadastre-se</a></p>
                </div>
                
                <?php if (isset($_GET['login']) && $_GET['login'] === 'erro'): ?>
                    <div class="text-danger">Usuário ou senha inválido(s)!</div>
                <?php endif; ?>

                <?php if (isset($_GET['usuario']) && $_GET['usuario'] === 'sucesso'): ?>
                    <div class="text-success">Cadastrado com sucesso!</div>
                <?php endif; ?>

                <?php if (isset($_GET['login']) && $_GET['login'] === 'erro2'): ?>
                    <div class="text-danger">Login obrigatório!</div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Sobre Nós</h3>
                <p>A SkateShop é a maior loja de skate online do Brasil, oferecendo os melhores produtos para skatistas desde 2010.</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 SkateShop. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>