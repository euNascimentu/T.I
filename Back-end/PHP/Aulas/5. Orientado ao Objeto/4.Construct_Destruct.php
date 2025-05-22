<?php 

class Caneta{

    public $modelo;
    public $cor;
    public $marca;

    function __construct($modelo,$cor,$marca){
        $this-> modelo = $modelo;
        $this-> cor = $cor;
        $this-> marca = $marca;
    }

    function mostrarCaneta(){
        return "Sua caneta é: {$this->__get('modelo')} da marca: {$this->__get('marca')} e da cor: {$this->__get('cor')}";
    }
    
    function __destruct(){
        echo "<br> Foi destruida";
    }

    function __get($atributo){
        return $this->$atributo;
    }
}

$caneta1 = new Caneta('Ponta Fina','Preta','Bic');
echo $caneta1->mostrarCaneta();
?>