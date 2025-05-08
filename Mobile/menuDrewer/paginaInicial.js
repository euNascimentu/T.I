import React from 'react';

import { View, Text, StyleSheet, Image } from 'react-native';
const paginaInicial = () => {
  return (
    <View>
      <Image
        source={{ uri: 'https://i.pinimg.com/736x/23/99/77/2399773d1dc49466af3c555d545f1865.jpg' }}
        style={{ width: 200, height: 300, borderRadius: 20, alignSelf: 'center', marginBottom: 10, marginTop: 20 }}
      />
      <Text style={style.title}> Olá, me chamo Jhoew. Prazer!</Text>
      <Text style={style.QuemSou}>
        Sou seu guia turístico e estou aqui para te ajudar a conhecer os melhores pontos turísticos do mundo!{'\n'}
        Sou apaixonado por viajar e explorar novos lugares, e quero compartilhar essa paixão com você.{'\n'}
        Aqui comigo, você encontrará informações sobre os melhores destinos turísticos, dicas de viagem e muito mais.{'\n'}
      </Text>
      <Text style={style.comecar}>
        Podemos começar a nossa jornada juntos?
      </Text>
    </View>
  );
};

const style = StyleSheet.create({
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#4169E1',
    textAlign: 'center',
  },
  QuemSou: {
    fontSize: 20,
    fontWeight: 'bold',
    lineHeight: 24,
    marginTop: 8,
    color: '#333',
    textAlign: 'center',
    marginHorizontal: 40,
  },
  comecar: {
    height: 70,
    fontSize: 24,
    fontWeight: 'bold',
    lineHeight: 24,
    color: '#0000CD',
    textAlign: 'center',
    marginHorizontal: 40,
  }
});
export default paginaInicial; 