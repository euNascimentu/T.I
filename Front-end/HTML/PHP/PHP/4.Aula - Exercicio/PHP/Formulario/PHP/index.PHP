<!DOCTYPE html>
<html lang="Pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.CSS">
    <title> Formulario </title>
</head>
<body id="index">
    <form class="formulario" action="dados.PHP">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" placeholder="ex: Fernando">

        <label for="peso">Peso:</label>
        <input type="number" name="peso" placeholder="ex: 60">

        <label for="altura">Altura:</label>
        <input type="number" name="altura" placeholder="ex: 1.73" step="0.01">
        
        <button> Enviar </button>
    </form>
</body>
</html>