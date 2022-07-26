import React, {Component} from "react";
import { CSSTransition } from 'react-transition-group';
import './Modal.css';

class Modal extends Component {
    render(){
            return (
                <CSSTransition
                    in={this.props.show}
                    timeout={{enter:1000, exit:2000}}
                    mountOnEnter
                    unmountOnExit
                    classNames={{
                        enter:null,
                        enterActive:'modal-show',
                        enterDone:null,

                        exit:null,
                        exitActive:'modal-hide',
                        exitDone:null
                    }}
                >

                    <div className='Modal'>
                        <div className="Modal-title">{this.props.title}</div>
                        <div className="Modal-text">{this.props.text}</div>
                        <div className="Modal-buttons">
                            <button className="Modal-close">close</button>
                            <button className="Modal-accept">accept</button>
                        </div>
                    </div>  
                </CSSTransition>
            );
            
                 
    }
}

export default Modal;