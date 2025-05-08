<?php
    require_once "valida_acesso.php";
    require 'config.php';


    $sql = "DELETE FROM usuarios WHERE id_usuario = '{$_GET["id"]}';";
    $conexao-> query($sql);

    header('location: usuarios.php?delete=sucesso');

?>