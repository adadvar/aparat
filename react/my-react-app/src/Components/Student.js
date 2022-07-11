import React, {Component} from "react";
import PropTypes from 'prop-types';

export default class Student extends Component {
    static defaultProps = {
        age: 0
    }

    static propTypes = {
        age: PropTypes.number.isRequired
    }

    getColor = () => {
        return this.props.age >= 18 ? 'green' : 'red';
    }
    render(){
        return(
            <div style={{ background: this.getColor() }}>
                {this.props.name} [{this.props.age}]
            </div>
        )
    }
}