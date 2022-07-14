import React, { Component } from 'react';
import {Provider} from 'react-redux';
import './App.css';
import Counter from './Components/Counter';
import CounterButtons from './Components/CounterButtons';
import CounterStore from './redux/CounterStore';

class App extends Component { 
  
  render() {
    return (
      <Provider store={CounterStore}>
        <div className='app'>
          <Counter/>
          <CounterButtons />
        </div>
      </Provider>
    );
  }

 
}



export default App;