import * as React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import TelaInicial from './PaginaInicial';
import Pagina03 from './Pagina03';
import Pagina02 from './Pagina02';

const Tab = createBottomTabNavigator();
function App() {
  return(
  <NavigationContainer>
    <Tab.Navigator initalRouteName="TelaInicial">
      <Tab.Screen name="TelaInicial" component={TelaInicial} />
      <Tab.Screen name="Pagina01" component={Pagina03} />
      <Tab.Screen name="Pagina02" component={Pagina02} />
      </Tab.Navigator>
  </NavigationContainer>
  );
}
export default App;