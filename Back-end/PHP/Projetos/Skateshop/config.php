<?php
    
    define('HOST','localhost');
    define('USER','root');
    define('PASS','');
    define('BASE','skateshop');
    
    //Criar a string de conexão
    $conexao = new mysqli(HOST,USER,PASS,BASE);
    $conexao -> set_charset("utf8mb4");
?>