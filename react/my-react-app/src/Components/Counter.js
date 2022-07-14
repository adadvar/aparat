import React, {Component} from "react";
import { connect } from 'react-redux';

class Counter extends Component {
    render(){
        return  (
            <div className="counter">
                 { this.props.counter }
            </div>
        )
        
    }
}

const mapStateToProps = (state) => {
    return {
        counter: state.count
    };
};

export default connect(mapStateToProps)(Counter);