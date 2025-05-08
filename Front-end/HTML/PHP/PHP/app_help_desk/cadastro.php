<html>

<head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .card-login {
            padding: 30px 0 0 0;
            width: 350px;
            margin: 0 auto;
        }

        .card-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cadastro {
            display: flex;
            justify-content: end;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">

            <div class="card-login">
                <div class="card">
                    <div class="card-header">
                        Cadastrar
                        <img src="iconCadastro.png" width="40px" height="40px" alt="icone cadastro">
                    </div>
                    <div class="card-body">
                        <form action="valida_login.PHP">
                            <div class="form-group">
                                <input type="nome" class="form-control" placeholder="Nome Completo" name="nome">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="E-mail" name="email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Senha" name="senha">
                            </div>

                            <?php
                            //VALIDA SE O PARÂMETRO LOGIN EXISTE E SE FOI AUTENTICADO
                            if (isset($_GET['login']) && $_GET['login'] === 'erro') { ?>
                                <div class="text-danger"> Usuário ou senha inválido(s)! </div>
                            <?php } ?>

                            <?php
                            //VALIDA SE O USUARIO TENTOU ENTRAR EM OUTRA PAGINA SEM LOGAR
                            if (isset($_GET['login']) && $_GET['login'] === 'erro2') { ?>
                                <div class="text-danger"> login obrigatório!</div>
                            <?php } ?>

                            <button class="btn btn-lg btn-info btn-block" type="submit">Entrar</button>
                        </form>
</body>

</html>