<?php
    
    define('HOST','localhost');
    define('USER','root');
    define('PASS','');
    define('BASE','ControleFinanceiro');
    
    //Criar a string de conexão
    $conexao = new mysqli(HOST,USER,PASS,BASE);
    $conexao -> set_charset("utf8mb4");
?>