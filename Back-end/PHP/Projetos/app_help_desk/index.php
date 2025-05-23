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

    .cadastro {
      display: flex;
      justify-content: end;
    }

    .iconLogin{
      display: flex;
      justify-content: center;
    }

    .iconLogin {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
      <img src="logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
      App Help Desk
    </a>
  </nav>

  <div class="container">
    <div class="row">

      <div class="card-login">
        <div class="card">
          <div class="card-header">
            Login
          </div>
          <div class="card-body">
            <div class="iconLogin">
              <img src="iconLogin.png" width="140px" height="140px" alt="icone">
            </div>
            <form action="valida_login.PHP">
              <div class="form-group">
                <input type="email" class="form-control" placeholder="E-mail" name="email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Senha" name="senha">
              </div>
              <div class="cadastro">
                <a href="cadastro.php">Novo? Cadastre-se aqui!</a>
              </div>

              <?php
              //VALIDA SE O PARÂMETRO LOGIN EXISTE E SE FOI AUTENTICADO
              if (isset($_GET['login']) && $_GET['login'] === 'erro') { ?>
                <div class="text-danger"> Usuário ou senha inválido(s)! </div>
              <?php } ?>

              <?php
              //VALIDA SE O PARÂMETRO LOGIN EXISTE E SE FOI AUTENTICADO
              if (isset($_GET['usuario']) && $_GET['usuario'] === 'sucesso') { ?>
                <div class="text-success"> Cadastrado com sucesso! </div>
              <?php } ?>

              <?php
              //VALIDA SE O USUARIO TENTOU ENTRAR EM OUTRA PAGINA SEM LOGAR
              if (isset($_GET['login']) && $_GET['login'] === 'erro2') { ?>
                <div class="text-danger"> login obrigatório!</div>
              <?php } ?>

              <button class="btn btn-lg btn-info btn-block" type="submit">Entrar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</body>

</html>