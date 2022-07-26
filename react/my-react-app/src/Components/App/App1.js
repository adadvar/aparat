import React, { Component } from 'react';
import ArticleList from '../Article/ArticleList';
import Login from '../Login/Login';
import './App.css'
 
class App extends Component { 
  
  render() {
    return (
        <div className='App'>
            <Login/>
            <ArticleList/>
        </div>
    );
  }

 
}



export default App;