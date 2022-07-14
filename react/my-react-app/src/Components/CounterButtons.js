import React, {Component} from "react";
import {connect} from 'react-redux';
import { addAction, minusAction } from "../redux/reducers/CounterReducer";

class CounterButtons extends Component {

    render(){
        return (
            <>
                <button onClick={this.props.addToCounter}>+1</button>
                <button onClick={this.props.reduceCounter}>-1</button>
            </>
        );
    }
}

const mapDispatchToProps = (dispatch) => {
    return {
        addToCounter: () =>  dispatch(addAction()),
        reduceCounter: () =>  dispatch(minusAction()),
    };
};

export default connect(null, mapDispatchToProps)(CounterButtons);