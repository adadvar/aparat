import React, {Component} from "react";
import './User.css';

export default class User extends Component {
    removeMe = () => {
        this.props.onRemove(this.props.id);
    }

    render(){
        return (
            <div className="user">
                <div>
                    <label>Name: </label>
                    <span>{this.props.name}</span>
                </div>

                <div>
                    <label>Age: </label>
                    <span>{this.props.age}</span>
                </div>

                <div>
                    <label>Id: </label>
                    <span>{this.props.id}</span>
                </div>

                <button className="remove" onClick={this.removeMe}>X</button>
            </div>
        )
    }
}