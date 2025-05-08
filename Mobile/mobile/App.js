import React,{Component} from "react";
import { View, Text, Image} from "react-native";
class App extends Component{
render(){
  return(
    <View>
      <Text style={{
        color:'red',fontSize:25,marginTop:50,
        fontWeight:'bold',//negrito
        fontStyle:'italic', //italico
        fontFamily:'Arial',//fonte
        textAlign:'center', //centralizar
        lineHeight:30,//altura da linha
        letterSpacing:2,//epaçamento entre as letras
        textDecorationLine:'underline', //adiciona uma linha sublinhada
        textDecorationStyle:'dotted',//estilo do sublinhado
        textDecorationColor:'#00ff00',//cor do sublinado
        textTransform:'uppercase',//todo texto em maiúscula
        textShadowColor:'#000000',//Cor de sombra
        textShadowRadius:10,
        textShadowOffset:{width:0,height:0}//Deslocamento da sombra
        }}>
        Hello,World!
      </Text>
      <View  style={{display:"flex", flexWrap:"wrap", flexDirection:"row", justifyContent: "center", alignContent:"center"}}>
      <Image
      source={{uri: 'https://i.pinimg.com/736x/a4/b0/f0/a4b0f0a77eeba24654d8f5e7698d0802.jpg'}}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>

      <Image 
      source={require('./assets/Logo.png')}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>

      <Image 
      source={{uri: 'https://i.pinimg.com/736x/90/68/d3/9068d39a1cc4173c33a69bd11a127fab.jpg'}}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>

      <Image 
      source={{uri: 'https://i.pinimg.com/736x/31/f3/b0/31f3b044e2dfbe941f655efb7e75e844.jpg'}}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>

      <Image 
      source={{uri: 'https://i.pinimg.com/736x/b7/08/e7/b708e7ccef4caba4435ba7b568a6a7d5.jpg'}}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>

      <Image 
      source={{uri: 'https://i.pinimg.com/736x/0c/a4/92/0ca492f26ea2c8e07eff13713b6154ea.jpg'}}
      style={{width:190, height:190, marginBottom:20}}>
      </Image>
      </View>
    </View>
  );
}
}
export default App;