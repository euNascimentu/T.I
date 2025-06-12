import React, { Component } from 'react';
import { StyleSheet, Text, View, TouchableOpacity, Image } from 'react-native';
 
export default class App extends Component {
  constructor(props) {
    super(props);
    this.state = {
      numero: 0,
      botao: 'VAI',
      ultimo: null
    };
 
    this.timer = null;
    this.vai = this.vai.bind(this);
    this.limpar = this.limpar.bind(this);
  }
 
  vai() {
    if (this.timer !== null) {
      // Parar o timer
      clearInterval(this.timer);
      this.timer = null;
      this.setState({ botao: 'VAI' });
    } else {
      // Começar o timer
      this.timer = setInterval(() => {
        this.setState({ numero: this.state.numero + 0.1 });
      }, 100);
      this.setState({ botao: 'PARAR' });
    }
  }
 
  limpar() {
    if (this.timer !== null) {
      clearInterval(this.timer);
      this.timer = null;
    }
 
    this.setState({
      ultimo: this.state.numero.toFixed(1),
      numero: 0,
      botao: 'VAI'
    });
  }
 
  render() {
    return (
      <View style={styles.container}>
        <Image
          source={{ uri: 'https://cdn-icons-png.flaticon.com/512/2088/2088617.png' }}
          style={styles.img}
        />
 
        <Text style={styles.timer}>{this.state.numero.toFixed(1)}</Text>
 
        <View style={styles.btnArea}>
          <TouchableOpacity style={styles.btn} onPress={this.vai}>
            <Text style={styles.btnTexto}>{this.state.botao}</Text>
          </TouchableOpacity>
 
          <TouchableOpacity style={styles.btn} onPress={this.limpar}>
            <Text style={styles.btnTexto}>LIMPAR</Text>
          </TouchableOpacity>
        </View>
 
        {this.state.ultimo > 0 && (
          <Text style={styles.ultimo}>
            Último tempo: {this.state.ultimo}s
          </Text>
        )}
      </View>
    );
  }
}
 
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#222',
    alignItems: 'center',
    justifyContent: 'center'
  },
  timer: {
    marginTop: -160,
    color: '#FFF',
    fontSize: 65,
    fontWeight: 'bold'
  },
  btnArea: {
    flexDirection: 'row',
    marginTop: 100,
    height: 40
  },
  btn: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#121212',
    height: 40,
    margin: 17,
    borderRadius: 9
  },
  btnTexto: {
    color: '#FFF',
    fontSize: 20,
    fontWeight: 'bold'
  },
  ultimo: {
    marginTop: 30,
    color: '#FFF',
    fontSize: 18,
    fontStyle: 'italic'
  },
  img: {
    width: 100,
    height: 100,
    marginBottom: 30
  }
});