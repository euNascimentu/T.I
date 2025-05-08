import 'react-native-gesture-handler';
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createDrawerNavigator } from '@react-navigation/drawer';
import pagina01 from './pagina01';
import pagina02 from './pagina02';
import pagina03 from './pagina03';
import pagina04 from './pagina04';
import paginaInicial from './paginaInicial';
const Drawer = createDrawerNavigator();
function App(){
  return (
    <NavigationContainer>
      <Drawer.Navigator initialRouteName="Quem sou eu?">
        <Drawer.Screen name="Quem sou eu?" component={paginaInicial} />
        <Drawer.Screen name="Grécia" component={pagina01} />
        <Drawer.Screen name="Dubai" component={pagina02} />
        <Drawer.Screen name="Madrid" component={pagina03} />
        <Drawer.Screen name="Veneza" component={pagina04} />
      </Drawer.Navigator>
    </NavigationContainer>
  );
} export default App;