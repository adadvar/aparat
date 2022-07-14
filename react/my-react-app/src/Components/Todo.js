import React, {Component} from "react";
import {connect} from 'react-redux';
import Addtodo from './AddTodo';
import {removeTodo} from '../redux/todoStore';

  
class Todo extends Component {

    renderItems = () => {
        return this.props.items.map(item => (
            <div key={item.ir}>
                <b>{item.title}</b>
                <button
                    style={{display: 'inline-block', color: 'red', marginLeft: '15px'}}
                    onClick={() => this.deleteTodo(item.id)}>X
                </button>
            </div>
        ))
    }

    deleteTodo = (id) => {
        this.props.removeItem(id);
    }

    render(){

        return (
            <>
                <Addtodo addItem={this.addTodo}/>
                {this.renderItems()}
            </>
        );
    }
}

const mapStateToProps = (state) => {
    return ({ 
        items: state.items
    });
}

const mapDispatchToProps = (dispatch) => {
    return ({
        removeItem: (id) => dispatch(removeTodo(id))
    })
}

export default connect(mapStateToProps, mapDispatchToProps)(Todo);