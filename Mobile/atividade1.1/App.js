
import React, { Component } from "react";
import { View, Text, Image } from 'react-native';
class App extends Component {
  render() {
    return (
      <View>
        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial',
          lineHeight: 100,//altura da linha
        }}>
          Fernando Nascimento
        </Text>

        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial',
        }}>
          Idade: 20 anos,
        </Text>
        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial',
        }}>
          Data de nascimento: 14/02/2005
        </Text>
        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial',
        }}>
        CPF:400.289.220-00
        </Text>
        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial',
        }}>
        Endereço:Rua do paraiso, 564
        </Text>
        <Text style={{
          color: "black",
          fontSize: 30,
          textAlign: 'center',
          fontWeight: 'bold',
          fontStyle: 'italic',
          fontFamily: 'Arial'
        }}>
        Objetivo: Conquista conhecimento e habilidades proficionais
        dentro da empresa.
        </Text>
      </View>
    );
  }
}
export default App;