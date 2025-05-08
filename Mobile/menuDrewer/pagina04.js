import React from "react";
import { View, Text, StyleSheet, Image, ScrollView } from "react-native";

const pagina01 = () => {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.title}>Veneza - A Cidade dos Canais</Text>
      
      <Text style={styles.introText}>
        Veneza é uma cidade única no mundo, construída sobre 118 pequenas ilhas conectadas por canais e pontes.
        Conhecida por sua arquitetura romântica, passeios de gôndola e o famoso Carnaval de Veneza, a cidade
        oferece uma experiência mágica que parece ter parado no tempo.
      </Text>

      {/* Primeiro ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>1. Praça de São Marcos</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/f1/60/52/f160520b2277ff824eb13e4de13e4468.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          A Praça de São Marcos é o coração de Veneza e uma das praças mais famosas do mundo. Dominada pela Basílica
          de São Marcos com seus mosaicos dourados, a praça também abriga o Campanário com vista panorâmica da cidade
          e o Palácio Ducal. Os cafés históricos da praça, como o Florian, oferecem música ao vivo e a autêntica
          atmosfera veneziana.
        </Text>
      </View>

      {/* Segundo ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>2. Canal Grande</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/19/94/bd/1994bd2246bc00402ece37ad177a62c1.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          O Canal Grande é a principal via aquática de Veneza, em forma de "S" invertido. Ladeado por mais de 170
          palácios históricos, o canal oferece o melhor da arquitetura veneziana. A melhor forma de explorá-lo é
          a bordo de um vaporetto ou em um passeio de gôndola. Destaques incluem a Ponte de Rialto e a Basílica
          de Santa Maria della Salute.
        </Text>
      </View>

      <Text style={styles.funFact}>
        Curiosidade: Veneza afunda cerca de 1-2mm por ano devido ao aumento do nível do mar e ao afundamento natural do solo.
      </Text>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flexGrow: 1,
    padding: 20,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#0d5b8c',
    marginBottom: 15,
    textAlign: 'center',
  },
  introText: {
    fontSize: 16,
    lineHeight: 24,
    marginBottom: 25,
    color: '#333',
    textAlign: 'justify',
  },
  attractionContainer: {
    marginBottom: 30,
  },
  attractionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#e63946',
    marginBottom: 10,
  },
  image: {
    width: '100%',
    height: 200,
    borderRadius: 8,
    marginBottom: 10,
  },
  description: {
    fontSize: 15,
    lineHeight: 22,
    color: '#555',
    textAlign: 'justify',
  },
  funFact: {
    fontSize: 16,
    fontStyle: 'italic',
    color: '#0d5b8c',
    marginTop: 10,
    textAlign: 'center',
  },
});

export default pagina01;