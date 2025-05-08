import React, { useState } from "react";
import { StyleSheet, Text, View, Modal, TouchableOpacity } from "react-native";

//define o componente principal
const App = () => {
  //Declara o estado modalVisible e a função para controlar o modal
  const [modalVisible, setModalVisible] = useState(false);
  //função para abrir o modal
  const abrirMenu = () => {
    setModalVisible(true);
  }
  //função para fechar o menu
  const fecharMenu = () => {
    setModalVisible(false);
  }
  //função para realizar uma ação quando a opção é selecionada
  const acaoSelecionada = (acao) => {
    //exibe um alerta com a ação selecionada
    alert('Ação selecionada: ${acao}');
    //fechar o menu
    fecharMenu();
  }
  return (
    //view principal
    <View style={styles.container}>
      {/* Botão para abrir o menu */}
      <TouchableOpacity style={styles.botaoAcao} onPress={abrirMenu}>
        <Text style={styles.buttonText}>Abrir Menu</Text>
      </TouchableOpacity>
      {/* Modal que exibe o menu ações */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={fecharMenu}>

        {/* Container para centralizar o modal */}
        <View style={styles.modalContainer}>
          {/* View que contem os itens do menu */}
          <View sytle={styles.MenuContainer}>
            {/* Opção do menu "Ver detalhes" */}
            <TouchableOpacity style={styles.itemMenu} onPress={() => acaoSelecionada('Ver detalhes')}>
              <Text style={styles.itemMenuText}>Ver detalhes</Text>
            </TouchableOpacity>
            {/* Opção do menu compartilhar */}
            <TouchableOpacity style={styles.itemMenu} onPress={() => acaoSelecionada('Compartilhar')}>
              <Text style={styles.itemMenuText}>Compartilhar</Text>
            </TouchableOpacity>
            {/* Opção do menu "Editar" */}
            <TouchableOpacity style={styles.itemMenu} onPress={() => acaoSelecionada('Editar')}>
              <Text style={styles.itemMenuText}>Editar</Text>
            </TouchableOpacity>
            {/* Opção do menu "Excluir" */}
            <TouchableOpacity style={styles.itemMenu} onPress={() => acaoSelecionada('Excluir')}>
              <Text style={styles.itemMenuText}>Excluir</Text>
            </TouchableOpacity>
            {/* Opção do menu "Cancelar" */}
            <TouchableOpacity style={styles.itemMenu} onPress={fecharMenu}>
              <Text style={styles.itemMenuText}>Cancelar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </View >
  );
};
export default App;
//Style dos componentes
const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#F8FAFF", // Fundo mais claro e clean
  },
  // Botão principal (estilo moderno com degradê)
  botaoAcao: {
    backgroundColor: "#4A6CF7", // Azul mais vibrante
    paddingVertical: 16,
    paddingHorizontal: 32,
    borderRadius: 12,
    shadowColor: "#4A6CF7",
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.2,
    shadowRadius: 16,
    elevation: 10,
  },
  buttonText: {
    color: "#FFF",
    fontSize: 16,
    fontWeight: "600",
    letterSpacing: 0.5, // Espaçamento entre letras
  },
  // Modal (fundo blur escuro)
  modalContainer: {
    flex: 1,
    justifyContent: "flex-end",
    alignItems: "center",
    backgroundColor: "rgba(0,0,0,0.7)", // Mais opaco
  },
  // Container do menu (estilo "neumorphism" ou glassmorphism)
  MenuContainer: {
    width: "75%",
    backgroundColor: "rgba(255, 255, 255, 0.95)", // Efeito "glass"
    borderRadius: 20,
    paddingVertical: 8,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.1,
    shadowRadius: 20,
    elevation: 10,
    borderWidth: 1,
    borderColor: "rgba(255, 255, 255, 0.3)", // Borda sutil para efeito de profundidade
    overflow: "hidden", // Para garantir que o borderRadius funcione
  },
  // Itens do menu (com ícone e transição suave)
  itemMenu: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 16,
    paddingHorizontal: 24,
    borderBottomWidth: 1,
    borderBottomColor: "rgba(255, 255, 255, 0.39)", // Linha quase invisível
  },
  itemText: {
    fontSize: 16,
    color: "#4A6CF7", // Cinza escuro elegante
    fontWeight: "500",
    marginLeft: 16, // Espaço após o ícone
  },
  // Efeito de toque (com animação suave)
  itemPressed: {
    backgroundColor: "rgba(74, 108, 247, 0.1)", // Azul clarinho ao tocar
  },
  // Ícone do item (opcional, usando @expo/vector-icons)
  itemIcon: {
    width: 24,
    height: 24,
    tintColor: "#4A6CF7", // Cor do ícone
  },
});