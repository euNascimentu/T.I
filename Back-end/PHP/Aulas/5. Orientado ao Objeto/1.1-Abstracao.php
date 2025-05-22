<?php 

//modelo
class Personagem {

    //atributos
    public $nome;
    public $idade;
    public $caracteristica;

    //métodos
    function resumirCard() {
        return "$this->nome, Idade $this->idade, Característica $this->caracteristica";
    }

    function modificarNome($nome) {
        $this->nome = $nome;
    }
    
    function modificarIdade($idade) {
        $this->idade = $idade;
    }

    function modificarCaracteristica($caracteristica) {
        $this->caracteristica = $caracteristica;
    }
}

$person1 = new Personagem(); //Instanciei minha classe em um objeto
echo $person1->resumirCard();
echo "<br>";
$person1->modificarNome("Goku");
$person1->modificarIdade(30);
$person1->modificarCaracteristica("Super Saiyajin");

echo "<br>";
$person2 = new Personagem(); //Instanciei minha classe em um objeto
echo $person2->resumirCard();
echo "<br>";
$person2->modificarNome("Cleiton");
$person2->modificarIdade(30);
$person2->modificarCaracteristica("Super Saiyajin");



?>