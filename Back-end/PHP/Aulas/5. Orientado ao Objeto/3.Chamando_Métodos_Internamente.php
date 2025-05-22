<?php 

class Carro{

    //Métodos
    public $nome = null;
    public $ano = null;
    public $cor = null;

    function __set($atributo, $valor){
       $this->$atributo = $valor;
    }

    function __get($atributo){
        return $this->$atributo;
    }

    function mostrarCarro(){
        return "Nome: {$this->__get('nome')} / Ano: {$this->__get('ano')} / Cor: {$this->__get('cor')} <hr><br/>";
    }
}

$carro1 = new Carro();
// echo $carro1->mostrarCarro();

$carro1-> __set("nome","HB20");
$carro1-> __set("ano","2023");
$carro1-> __set("cor","Prata");
echo $carro1->mostrarCarro();

//------------------------------

$carro2 = new Carro();

$carro2-> __set("nome","Ferrari");
$carro2-> __set("ano","2025");
$carro2-> __set("cor","Vermelho");
echo $carro2->mostrarCarro();

//------------------------------

$carro3 = new Carro();

$carro3-> __set("nome","Ka");
$carro3-> __set("ano","2017");
$carro3-> __set("cor","Preto");
echo $carro3->mostrarCarro();
?>