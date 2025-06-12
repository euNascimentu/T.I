import React,{useState} from "react";
import { View, Text, StyleSheet, TouchableOpacity, Image } from "react-native";
 
//objeto que contem as imagens
const options={
  pedra: require("./assets/pedra.png"),
  papel: require("./assets/papel.png"),
  tesoura: require("./assets/tesoura.png"),
};
//componente principal
const App = () =>{
  //serve para armazenar a escolha do jogador
  const[playerChoice, setPlayerChoice] = useState(null);
  const[computerChoice, setComputerChoice] = useState(null);
  //armazena o resultado
  const [result, SetResult] = useState('');
  //lógica do jogo
  const playGame = (playerSelection) =>{
    //array com as opções disponiveis do jogo
    const choices = ['pedra','papel','tesoura'];
    //escolha aleatória do computador
    const computerSeletion = choices[Math.floor(Math.random()*3)];
    //atualizar os estado de escolha de cada jogador
    setPlayerChoice(playerSelection);
    setComputerChoice(computerSeletion);
    //determina o ganhador
    if (playerSelection === computerSeletion){
      SetResult('Empate!');
    }
    else if(
      (playerSelection==='pedra' && computerSeletion === 'tesoura') ||
      (playerSelection==='papel' && computerSeletion === 'pedra') ||
      (playerSelection==='tesoura' && computerSeletion === 'papel')
    ){
      SetResult('Você venceu!');
      }
      else{
        SetResult('Você perdeu!');
      }
  };
 
  return(
    <View style={styles.container}>
      <Text sttyle={styles.title}>Pedra, Papel e Tesoura</Text>
      <View style={styles.choiceContainer}>
        <View styles={styles.choice}>
        <Text style={styles.choiceText}>Você</Text>
        {playerChoice && (
          <Image source={options[playerChoice]}
        style={styles.choiceImage}/>)}
      </View>
      <Text style={styles.vsTexto}>VS</Text>
      <View style={styles.choiceText}>
        <Text style={styles.choiceText}>Computador</Text>
        {computerChoice && (
          <Image source={options[computerChoice]}
        style={styles.choiceImage}/>)}
        </View>
        </View>
        <Text style={styles.resultText}>{result}</Text>
        <View style={styles.buttomsContainer}>
          {Object.keys(options).map((option)=>(
            <TouchableOpacity
            key={option}
            style={styles.button}
            onPress={()=>playGame(option)}>
              <Text style={styles.buttonText}>{option.toLocaleUpperCase()}</Text>
            </TouchableOpacity>
           
          ))}
        </View>
    </View>
  );
};
const styles = StyleSheet.create({
  container: {
    flex: 1, // Faz o contêiner ocupar toda a tela
    justifyContent: 'center', // Centraliza verticalmente o conteúdo
    alignItems: 'center', // Centraliza horizontalmente o conteúdo
    backgroundColor: '#f5f5f5', // Cor de fundo
  },
  title: {
    fontSize: 24, // Tamanho da fonte
    fontWeight: 'bold', // Deixa o texto em negrito
    marginBottom: 20, // Espaçamento inferior
  },
  choicesContainer: {
    flexDirection: 'row', // Alinha os itens lado a lado
    alignItems: 'center', // Alinha verticalmente ao centro
    marginBottom: 20, // Espaçamento inferior
  },
  choice: {
    alignItems: 'center', // Centraliza os itens horizontalmente
    marginHorizontal: 20, // Espaçamento horizontal entre as escolhas
  },
  choiceText: {
    fontSize: 18, // Tamanho da fonte
    fontWeight: 'bold', // Deixa o texto em negrito
    marginBottom: 10, // Espaçamento inferior
  },
  choiceImage: {
    width: 80, // Largura da imagem
    height: 80, // Altura da imagem
  },
  vsText: {
    fontSize: 24, // Tamanho da fonte
    fontWeight: 'bold', // Deixa o texto em negrito
  },
  resultText: {
    fontSize: 20, // Tamanho da fonte
    fontWeight: 'bold', // Deixa o texto em negrito
    marginVertical: 20, // Espaçamento vertical
  },
  buttonsContainer: {
    flexDirection: 'row', // Alinha os botões lado a lado
    justifyContent: 'space-around', // Distribui os botões uniformemente
    width: '100%', // Ocupa toda a largura do contêiner
    paddingHorizontal: 20, // Espaçamento horizontal interno
  },
  button: {
    backgroundColor: '#007bff', // Cor de fundo do botão
    padding: 10, // Espaçamento interno
    borderRadius: 5, // Borda arredondada
    marginHorizontal: 10, // Espaçamento horizontal entre os botões
  },
  buttonText: {
    color: '#fff', // Cor do texto (branco)
    fontSize: 16, // Tamanho da fonte
    fontWeight: 'bold', // Deixa o texto em negrito
  },
});
 
// Exporta o componente principal para ser usado na aplicação
export default App;