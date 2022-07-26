import React, { Component } from 'react';
import { TransitionGroup, CSSTransition } from 'react-transition-group';
import Modal from '../Modal/Modal';
import './App.css'
 
class App extends Component { 
  
  state ={
    showModal: false, 
    list : [1,2,3]
  }

  showModal = () => {
    this.setState(oldState => ({
      ...oldState,
      showModal: !oldState.showModal
    }));
  }

  addListItem = () => {
    this.setState(oldState => ({
      ...oldState,
      list: [...oldState.list, Math.ceil(Math.random()*100)]
    }));
  }

  removeListItem = (item) => {
    this.setState(oldState => ({
      ...oldState,
      list: oldState.list.filter(item1 => item !== item1)
    }));
  }

  renderListItems = () => {
    return this.state.list.map((item, index) => (
      <CSSTransition
       key={index} 
       timeout={500} 
       classNames='fade'
       mountOnEnter
       unmountOnExit>
        <li className="ListItem" onClick={() => this.removeListItem(item)}>
          {item}
        </li>
      </CSSTransition>
    ));
  }

  render() {
    return (
        <div className='App'>
          <button onClick={this.showModal}>show / hide modal</button>

            <Modal
                title="modal title" 
                text=" sample text"
                show={this.state.showModal}
              />

          <button onClick={this.addListItem}>add</button>
          <TransitionGroup component='ul' className='List'>
            {this.renderListItems()}
          </TransitionGroup>
              
        </div>
    );
  }

 
}



export default App;