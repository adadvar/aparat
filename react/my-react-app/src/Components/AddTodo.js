import React, {Component} from "react";
import { connect } from 'react-redux';

import {addTodo} from '../redux/todoStore'
class AddTodo extends Component {
    state = {
        enableButton: false
    }

    inputBox = null;

    AddTodoHandler = () => {
        this.props.addItem({
            'title': this.inputBox.value
        });
        this.inputBox.value = '';
        this.inputChange();
    }

    inputChange = () => {
        this.setState((oldState) => ({
            ...oldState,
            enableButton: this.inputBox.value.trim() !== ''
        }))
    }

    render(){
        return (
            <>
                <input ref={el => this.inputBox=el} onChange={this.inputChange}/>
                <button onClick={this.AddTodoHandler} disabled={!this.state.enableButton}>Add</button>
            </>
        )
    }
}

const mapDispatchToProps = (dispatch) => {
    return ({
        addItem: (todo) => dispatch(addTodo(todo))
    })
}

export default connect(null, mapDispatchToProps)(AddTodo)