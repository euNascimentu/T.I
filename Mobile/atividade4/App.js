import React,{useState} from "react";
import { View, TextInput, Text, Button, Alert, StyleSheet, TouchableOpacity  } from "react-native";
 
 
 
const form = () => {
  const[nome, setNome] = useState('');
  const[email, setEmail] = useState('');
  const[idade, setIdade] = useState('');
 
 
const Enviar = () => {
  Alert.alert('Dados Submetidos',`Nome: ${nome}\nEmail: ${email} \nIdade: ${idade}`);
};
 
return (
  <View  style={styles.container}>
  <Text>Nome:</Text>
  <TextInput
  style={styles.input}
  placeholder="Digite o seu nome"
  value={nome}
  onChange={setNome}>
  </TextInput>
  <Text>Email:</Text>
  <TextInput
  style={styles.input}
  placeholder="Digite o seu email"
  value={email}
  onChangeText={setEmail}
  keyboardType="email-address"></TextInput>
  <Text>Idade:</Text>
  <TextInput
  style={styles.input}
  placeholder="Digite a sua idade"
  value={idade}
  onChangeText={setIdade}
  keyboardType="numeric"></TextInput>
  <Button title="Enviar" onPress={Enviar}></Button>
    </View>
);
};
 
const styles = StyleSheet.create({
  container:{
    flex:1,
    padding:16,
    justifyContent:'center',
    backgroundColor:'#f5f5f5',
  },
  input:{
    height:40,
    borderColor:'gray',
    borderWidth:1,
    marginBottom:12,
    paddingHorizontal:8,
  },
});
 
export default form;