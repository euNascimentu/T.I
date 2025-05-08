import React  from "react";

import { View, Text, StyleSheet, ScrollView, Image } from "react-native";
const pagina01 = () => {
  return (
    <ScrollView contentContainerStyle={styles.container}>
    <Text style={styles.title}>Grécia - Berço da Civilização Ocidental</Text>
    
    <Text style={styles.introText}>
      A Grécia é um país de beleza incomparável, onde a história antiga se mistura com paisagens deslumbrantes.
      Conhecida por suas ilhas paradisíacas, monumentos históricos e gastronomia marcante, a Grécia atrai
      milhões de visitantes todos os anos.
    </Text>

    {/* Primeiro ponto turístico */}
    <View style={styles.attractionContainer}>
      <Text style={styles.attractionTitle}>1. Acrópole de Atenas</Text>
      <Image
        source={{ uri: 'https://i.pinimg.com/736x/5f/a6/9f/5fa69f51aea031f8fa5576038d383882.jpg' }}
        style={styles.image}
      />
      <Text style={styles.description}>
        A Acrópole de Atenas é o sítio arqueológico mais importante da Grécia. Construída no século V a.C.,
        abriga o famoso Partenon, templo dedicado à deusa Atena. Localizada no topo de uma colina, oferece
        vistas panorâmicas de Atenas. O complexo inclui ainda o Erecteion, com suas célebres Cariátides, e
        o Propileu, a monumental entrada da Acrópole.
      </Text>
    </View>

    {/* Segundo ponto turístico */}
    <View style={styles.attractionContainer}>
      <Text style={styles.attractionTitle}>2. Santorini</Text>
      <Image
        source={{ uri: 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Oia%2C_Santorini_HDR_sunset.jpg/800px-Oia%2C_Santorini_HDR_sunset.jpg' }}
        style={styles.image}
      />
      <Text style={styles.description}>
        Santorini é uma das ilhas mais famosas do mundo, conhecida por suas casas brancas com cúpulas azuis
        e vistas espetaculares do mar Egeu. Formada por uma erupção vulcânica, a ilha oferece praias de areia
        vulcânica, vinhedos únicos e pôr-do-sol inesquecíveis em Oia. Fira, a capital, é repleta de lojas,
        restaurantes e vida noturna animada.
      </Text>
    </View>

    <Text style={styles.funFact}>
      Curiosidade: A Grécia tem mais de 6.000 ilhas, mas apenas 227 são habitadas!
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