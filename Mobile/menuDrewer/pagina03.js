import React from "react";
import { View, Text, StyleSheet, Image, ScrollView } from "react-native";

const pagina01 = () => {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.title}>Madrid - O Coração Vibrante da Espanha</Text>
      
      <Text style={styles.introText}>
        Madrid, a capital espanhola, é uma cidade que pulsa com energia, arte e história. Com seus grandiosos museus,
        arquitetura impressionante e vida noturna animada, Madrid oferece uma autêntica experiência da cultura espanhola.
        Suas praças encantadoras, tapas deliciosas e o famoso estilo de vida madrilenho fazem desta cidade um destino
        imperdível na Europa.
      </Text>

      {/* Primeiro ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>1. Palácio Real de Madrid</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/25/64/71/2564712f26ab16c5adc296eefe4194ab.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          O majestoso Palácio Real de Madrid é a residência oficial da família real espanhola, embora seja usado
          principalmente para cerimônias oficiais. Com mais de 3.000 cômodos, é o maior palácio real da Europa Ocidental.
          Os visitantes podem admirar sua impressionante arquitetura barroca, os luxuosos apartamentos reais, a coleção
          de armaduras e a famosa Sala do Trono. Os jardins de Sabatini e o Campo del Moro oferecem belos espaços verdes
          ao redor do palácio.
        </Text>
      </View>

      {/* Segundo ponto turístico */}
      <View style={styles.attractionContainer}>
        <Text style={styles.attractionTitle}>2. Museu do Prado</Text>
        <Image
          source={{ uri: 'https://i.pinimg.com/736x/72/62/bc/7262bcfe7fffc2b55192fb0afb7a7e4f.jpg' }}
          style={styles.image}
        />
        <Text style={styles.description}>
          O Museu do Prado é um dos mais importantes museus de arte do mundo, com uma coleção que abrange desde o século XII
          até o início do século XX. O museu abriga obras-primas de artistas espanhóis como Velázquez, Goya e El Greco,
          além de grandes nomes internacionais como Bosch, Rubens e Ticiano. Destaques incluem "As Meninas" de Velázquez,
          "O 3 de Maio de 1808" de Goya e "O Jardim das Delícias Terrenas" de Bosch. O edifício neoclássico em si é uma
          obra de arte que vale a pena admirar.
        </Text>
      </View>

      <Text style={styles.funFact}>
        Curiosidade: Madrid é a única capital europeia que tem uma estátua do Diabo - o "Ángel Caído" no Parque del Retiro!
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
    color: '#c60b1e', // Vermelho da bandeira espanhola
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
    color: '#ffc400', // Amarelo da bandeira espanhola
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
    color: '#c60b1e',
    marginTop: 10,
    textAlign: 'center',
  },
});

export default pagina01;