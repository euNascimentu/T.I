<?php 

//modelo
class Skate {

    //atributos
    public $shape = null;
    public $roda = null;
    public $truck = null;

    //métodos

    //Padrao
    function setShape($shape){
        $this->shape = $shape;
    }

    function getShape(){
        return $this->shape;
    }
    
    function setRoda($roda){
        $this->roda = $roda;
    }

    function getRoda(){
        return $this->roda;
    }
    
    function setTruck($truck){
        $this->truck = $truck;
    }

    function getTruck(){
        return $this->truck;
    }

    function resumirCard(){
        return "Shape: $this->shape, Roda: $this->roda, Truck: $this->truck";
    }


    // __SET e __GET "Magicos"
    function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    function __get($atributo){
        return $this->$atributo;
    }
}

$skate1 = new Skate(); //Instanciei minha classe em um objeto
echo $skate1->resumirCard();
echo "<br>";
$skate1->setShape("Shape 8.0");
$skate1->setRoda("Roda 52mm");
$skate1->setTruck("Truck 139");
echo $skate1->resumirCard();

echo "<br>";
echo "<br>";

echo "!Usando o __SET e __GET! <br>";

$skate2 = new Skate();
echo $skate2->resumirCard();
echo "<br>";
$skate2-> __set("shape", "Shape 8.5");
$skate2-> __set("roda", "Roda 54mm");
$skate2-> __set("truck", "Truck 149");
echo $skate2->resumirCard();

echo "<br>";
echo "<br>";

$skate3 = new Skate();
echo $skate3->resumirCard();
echo "<br>";
$skate3-> __set("shape", "Shape 9.0");
$skate3-> __set("roda", "roda 56mm");
$skate3-> __set("truck", "Truck 151");
echo $skate3->resumirCard();
?>