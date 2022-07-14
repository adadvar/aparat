import React, { Component } from 'react';
import { Provider } from 'react-redux';
import './App.css'
import Todo from './Components/Todo';
import todoStore from './redux/todoStore';

class App extends Component { 
  
  render() {
    return (
      <Provider store = {todoStore}>
        <>
          <Todo />
        </>
      </Provider>
    );
  }

 
}



export default App;