<!DOCTYPE html>
<html lang="Pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Formulario </title>
</head>
<body>
    <form action="dados.PHP">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" placeholder="ex: Fernando">

        <label for="peso">Peso:</label>
        <input type="number" name="peso" placeholder="ex: 60">

        <label for="altura">Altura:</label>
        <input type="number" name="altura" placeholder="ex: 1.73" step="any">
        
        <button> Enviar </button>
    </form>
</body>
</html>