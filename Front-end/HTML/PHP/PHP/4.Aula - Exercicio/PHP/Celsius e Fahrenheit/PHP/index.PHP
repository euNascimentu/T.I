<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.CSS">
    <script src="../Script/script.JS"></script>
    <title> Celsius e Fahrenheit</title>
</head>

<body>
    <header>
        © Fernando Nascimento
    </header>
    <section id="fundo">
        <form id="dados" action="resultado.PHP">
            <label class="lblNome" for="nome">Nome:</label>
            <input class="txtNome" type="text" name="nome" placeholder="ex: Fernando">

            <div id="fundoRdbs">
                <div>
                    <input id="rdbCelsius" type="radio" name="temp" onclick="mudarTexto()">
                    <label for="rdbCelsius">Celsius</label>
                </div>

                <div>
                    <input id="rdbFaren" type="radio" name="temp" onclick="mudarTexto()">
                    <label for="rdbFaren">Fahrenheit</label>
                </div>
            </div>

            <input type="text" id="lblTemp" name="temp2"></input>
            <input class="txtTemp" type="number" name="valor" placeholder="ex: 24°c / 173°f">

            <div id="fundoBotao">
                <button id="btnEnviar"> Enviar </button>
            </div>
        </form>
    </section>
</body>

</html>