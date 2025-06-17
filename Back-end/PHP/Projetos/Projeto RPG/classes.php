<?php 

abstract class carros{

    public $cor, $marca, $categoria, $velocidade, $modelo, $imagens;

    public function __set($atributo,$valor){
        $this->$atributo = $valor;
    }

    public function __get($atributo){
        return $this->$atributo;
    }
}

class R8 extends carros{
    
    public $marca = 'Audi';
    public $velocidade = '289km/h';
    public $categoria = 'Esportivo';
    public $imagens = 'R8';

}

class GLA200 extends carros{
    
    public $marca = 'Mercedes';
    public $velocidade = '210km/h';
    public $categoria = 'SUV';
    public $imagens = 'GLA200';

}

class PassatHighLine extends carros{
    
    public $marca = 'Volkswagem';
    public $velocidade = '246km/h';
    public $categoria = 'Sedan';
    public $imagens = 'Passat';

}

class GT3RS extends carros{
    
    public $marca = 'Porsche';
    public $velocidade = '296km/h';
    public $categoria = 'Esportivo';
    public $imagens = '911GT3';

}

class Compass  extends carros{
    
    public $marca = 'Jeep';
    public $velocidade = '210km/h';
    public $categoria = 'SUV';
    public $imagens = 'Compass';

}
?>