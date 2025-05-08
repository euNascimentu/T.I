import React from 'react';
import { StyleSheet ,View, Text } from 'react-native';
export default function App() {
    return (
      <View style={styles.container}>
        <View style={styles.header}>
          <Text>Cabeçalho Fixo</Text>
        </View>
        <View style={styles.main}>
          <Text>Conteudo Principal dinamico </Text>
        </View>
        <View style={styles.footer}>
          <Text> 1 </Text>
          <Text> 2 </Text>
          <Text> 3 </Text>
        </View>
      </View>
    );
  }

  const styles = StyleSheet.create({
    container: {
      flex:1,
    },
    header: {
      height:100,
      backgroundColor: 'lightgray',
      justifyContent:'center',
      alignItems:'center'
    },
    main: {
      flex:1,
      backgroundColor:'lightblue',
      justifyContent:'center',
      alignItems:'center'
    },
    footer: {
      height:50,
      backgroundColor: 'lightgreen',
      flexDirection:'row',
      justifyContent:'center',
      alignItems:'center',
      paddingHorizontal: 10,
    },
  })