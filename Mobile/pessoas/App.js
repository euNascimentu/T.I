import react, { useEffect, useState} from 'react'
import {
  View,
  Text,
  TextInput,
  Button,
  FlatList,
  StyleSheet,
  ActivityIndicator,
  Alert,
  Plataform
} from 'react-native';

const API_URL = Platform.OS === 'android'
? 'http;//192.168.56.1/api_react/pessoas.php'
: 'http://localhost/api_react/pessoas.php';

export default function App(){
  const [pessoas, setPessoas] = useState([]);
  const [nome, setNome] = useState('');
  const [idade, setIdade] = useState('');
  const [loading, setLoading] = useState(false);

  const buscarPessoas = async () => {
    setLoading(true);
    try {
      const response = await fetch(API_URL);
      const data = await response.json();
      setPessoas(data);
    } catch (error) {
      Alert.alert('Erro', 'Não foi possivel carregar as pessoas. Verifique sua API e o IP.');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const adicioinarPessoa = async () => {
    if (!nome.trim() || !idade.trim()) {
      Alert.alert('Atenção', 'Por Favor, preencha o nome e a idade.');
      return;
    }
    setLoading(true);
    try{
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ nome: nome, idade: parseInt(idade) }),
      });
     
      const data = await response.json();
 
      if (data.sucesso) {
        // Limpa os campos e busca a lista atualizada
        setNome('');
        setIdade('');
        buscarPessoas();
      } else {
        Alert.alert('Erro', data.mensagem || 'Ocorreu um erro ao adicionar.');
      }
 
    } catch (error) {
      Alert.alert('Erro', 'Não foi possível adicionar a pessoa.');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };
 
  // useEffect para buscar os dados quando o componente for montado
  useEffect(() => {
    buscarPessoas();
  }, []);
 
  return (
    <View style={styles.container}>
      <Text style={styles.titulo}>Cadastro de Pessoas</Text>
     
      <TextInput
        style={styles.input}
        placeholder="Nome da pessoa"
        value={nome}
        onChangeText={setNome}
      />
      <TextInput
        style={styles.input}
        placeholder="Idade"
        value={idade}
        onChangeText={setIdade}
        keyboardType="numeric"
      />
     
      <Button title={loading ? "Salvando..." : "Adicionar Pessoa"} onPress={adicionarPessoa} disabled={loading} />
 
      <Text style={styles.subtitulo}>Pessoas Cadastradas</Text>
 
      {loading && pessoas.length === 0 ? (
        <ActivityIndicator size="large" color="#0000ff" />
      ) : (
        <FlatList
          data={pessoas}
          keyExtractor={(item) => item.id.toString()}
          renderItem={({ item }) => (
            <View style={styles.itemLista}>
              <Text style={styles.itemTexto}>{item.nome}</Text>
              <Text style={styles.itemTexto}>{item.idade} anos</Text>
            </View>
          )}
          ListEmptyComponent={<Text style={styles.listaVazia}>Nenhuma pessoa cadastrada.</Text>}
        />
      )}
    </View>
  );
}
 
const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    paddingTop: 60,
    backgroundColor: '#f5f5f5',
  },
  titulo: {
    fontSize: 24,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 20,
  },
  subtitulo: {
    fontSize: 20,
    fontWeight: 'bold',
    marginTop: 30,
    marginBottom: 10,
    borderTopWidth: 1,
    borderTopColor: '#ccc',
    paddingTop: 20,
  },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 5,
    padding: 10,
    marginBottom: 10,
    fontSize: 16,
  },
  itemLista: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 5,
    marginBottom: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
    borderWidth: 1,
    borderColor: '#eee',
  },
  itemTexto: {
    fontSize: 18,
  },
  listaVazia: {
    textAlign: 'center',
    marginTop: 20,
    fontSize: 16,
    color: 'gray',
  }
});