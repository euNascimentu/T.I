import React from "react";
import { View, Text, StyleSheet, Image, ScrollView } from "react-native";

const pagina01 = () => {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.title}>Dubai - Oásis de Luxo e Inovação</Text>
      
      <Text style={styles.introText}>
        Dubai é a cidade dos superlativos, onde o deserto encontra o mar e a tradição se mistura com a mais alta tecnologia.
        Conhecida por seus arranha-céus futuristas, ilhas artificiais e opulência, Dubai é um destino que redefine o conceito
        de luxo e hospitalidade.
      </Text>

      {/* Primeiro ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>1. Burj Khalifa</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/b9/b9/e5/b9b9e59ed525a40f619d527a362324e1.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          O Burj Khalifa é o edifício mais alto do mundo, com impressionantes 828 metros de altura. Inaugurado em 2010,
          possui 163 andares e oferece uma vista panorâmica espetacular de Dubai a partir do observatório no 148º andar.
          O prédio abriga residências de luxo, o hotel Armani, escritórios e o famoso restaurante At.mosphere. À noite,
          o edifício se transforma em um espetáculo de luzes com seu sistema de iluminação inteligente.
        </Text>
      </View>

      {/* Segundo ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>2. Palm Jumeirah</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/50/e3/9a/50e39a21f888bbd647b1345fb8331869.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          Palm Jumeirah é uma das ilhas artificiais mais famosas do mundo, construída em forma de palmeira. Este projeto
          audacioso abriga hotéis luxuosos como o Atlantis The Palm, residências exclusivas e praias privativas. A ilha
          possui um calçadão de 5,4 km chamado The Boardwalk, com vista para o horizonte de Dubai. O Aquaventure Waterpark
          e o Dolphin Bay são atrações imperdíveis para famílias, oferecendo experiências aquáticas únicas.
        </Text>
      </View>

      <Text style={styles.funFact}>
        Curiosidade: Dubai tem o maior shopping do mundo - o Dubai Mall - com área equivalente a 200 campos de futebol!
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
    color: '#d4af37', // Dourado que remete ao luxo de Dubai
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
    color: '#c8102e', // Vermelho que remete às cores dos Emirados
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
    color: '#d4af37',
    marginTop: 10,
    textAlign: 'center',
  },
});

export default pagina01;