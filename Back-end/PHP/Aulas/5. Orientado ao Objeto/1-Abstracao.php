<?php 

//modelo
class Personagem {

    //atributos
    public $nome;
    public $idade;
    public $caracteristica;

    //métodos
    function resumirCard() {
        return "Nome: $this->nome, Idade: $this->idade, Característica: $this->caracteristica";
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
echo $person1->resumirCard();

echo "<br>";

$person2 = new Personagem();
echo $person2->resumirCard();
echo "<br>";
$person2->modificarNome("Vegeta");
$person2->modificarIdade(35);
$person2->modificarCaracteristica("Príncipe dos Saiyajins");
echo $person2->resumirCard();
?>