import React, { useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet, Alert, ImageBackground } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';

const Stack = createStackNavigator();

// Tela de Cadastro
function CadastroScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');

  const cadastrar = async () => {
    if (!email || !senha) {
      Alert.alert('Erro', 'Preencha todos os campos!');
      return;
    }

    try {
      await AsyncStorage.setItem('usuario', JSON.stringify({ email, senha }));
      Alert.alert('Sucesso!', 'Usuário cadastrado com sucesso!');
      navigation.navigate('Login');
    } catch (error) {
      Alert.alert('Erro', 'Falha ao cadastrar usuário');
    }
  };

  return (
    <ImageBackground 
      source={require('./assets/bgLogin.png')} 
      style={styles.backgroundImage}
      resizeMode="cover"
    >
      <View style={styles.container}>
        <Text style={styles.title}>Cadastro</Text>
        <TextInput
          style={styles.input}
          placeholder="Email"
          value={email}
          onChangeText={setEmail}
          keyboardType="email-address"
        />
        <TextInput
          style={styles.input}
          placeholder="Senha"
          secureTextEntry
          value={senha}
          onChangeText={setSenha}
        />
        <Button title="Cadastrar" onPress={cadastrar} />
        <Text style={styles.link} onPress={() => navigation.navigate('Login')}>
          Já tem conta? Faça Login
        </Text>
      </View>
    </ImageBackground>
  );
}

// Tela de Login
function LoginScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [senha, setSenha] = useState('');

  const fazerLogin = async () => {
    try {
      const usuarioSalvo = await AsyncStorage.getItem('usuario');

      if (!usuarioSalvo) {
        Alert.alert('Erro', 'Nenhum usuário cadastrado');
        return;
      }

      const { email: emailSalvo, senha: senhaSalva } = JSON.parse(usuarioSalvo);

      if (email === emailSalvo && senha === senhaSalva) {
        Alert.alert('Bem-vindo!', 'Login realizado com sucesso!');
      } else {
        Alert.alert('Erro', 'Email ou senha incorretos');
      }
    } catch (error) {
      Alert.alert('Erro', 'Falha ao fazer login');
    }
  };

  return (
    <ImageBackground 
      source={require('./assets/bgLogin.png')} 
      style={styles.backgroundImage}
      resizeMode="cover"
    >
      <View style={styles.container}>
        <Text style={styles.title}>Login</Text>
        <TextInput
          style={styles.input}
          placeholder="Email"
          value={email}
          onChangeText={setEmail}
          keyboardType="email-address"
        />
        <TextInput
          style={styles.input}
          placeholder="Senha"
          secureTextEntry
          value={senha}
          onChangeText={setSenha}
        />
        <Button title="Entrar" onPress={fazerLogin} />
        <Text style={styles.link} onPress={() => navigation.navigate('Cadastro')}>
          Não tem conta? Cadastre-se
        </Text>
      </View>
    </ImageBackground>
  );
}

// App Principal
export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator 
        initialRouteName="Login"
        screenOptions={{
          headerShown: false // Oculta o cabeçalho de navegação
        }}
      >
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Cadastro" component={CadastroScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

// Estilos atualizados
const styles = StyleSheet.create({
  backgroundImage: {
    flex: 1,
    width: '100%',
    height: '100%',
  },
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.1)', // Fundo semi-transparente para melhor legibilidade
  },
  title: {
    fontSize: 24,
    marginBottom: 25,
    textAlign: 'center',
    color: '#000', // Cor do texto mais visível
  },
  input: {
    height: 40,
    width: '55%',
    borderColor: 'gray',
    borderWidth: 1,
    marginBottom: 15,
    padding: 10,
    borderRadius: 5,
    backgroundColor: 'rgba(255, 255, 255, 0.9)'
  },
  link: {
    color: 'purple',
    textAlign: 'center',
    marginTop: 15,
    fontWeight: 'bold',
  },
});