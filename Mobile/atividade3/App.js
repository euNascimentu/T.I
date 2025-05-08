import React, { Component } from "react";
import { View, Text, Image, Alert, StyleSheet, TouchableOpacity, Button } from "react-native";

class App extends Component {
  render() {
    return (
      <View style={styles.container}>
        <Button
          color={"darkblue"}
          title="Clique aqui"
          onPress={() => Alert.alert('Você Apertou o botão')}>
        </Button>

        <TouchableOpacity style={styles.botao}
            onPress={() => alert('Clicou no botao')}>
            <Text style={styles.textBotao}>Enviar</Text>
          </TouchableOpacity>
      </View>

    );
  }
}

export default App;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop: 35,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'lightblue',
  },
  botao: {
    marginTop: 20,
    width: 100,
    height: 32,
    backgroundColor: 'darkblue',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: 3,
    // boxShadow:'0 0 10px darkred',
  },
  textBotao: {
    color: 'white',
  }
})