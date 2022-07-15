import React, {Component} from "react";
import {connect} from 'react-redux';
import { addToCard,removeFromCard } from "../redux/ShopStore";

class ShopItem extends Component {
   
    renderButton = () => {
        
        if(this.props.card.lastIndexOf(this.props.id) == -1){
            return <button onClick={()=>this.props.addToCard(this.props.id)}>add</button>
        } 
        return <button onClick={()=>this.props.removeFromCard(this.props.id)}>remove</button>;
}
    render(){
        return(
            <>
                <h3>{this.props.name}</h3>
                <div>
                    <b>{this.props.price}</b>
                    {
                      this.renderButton() 
                    }
                    
                </div>
            </>
        );
    }
}

const mapStateToProps = (state) => ({
    card: state.card
});

const mapDispatchTiProps = (dispatch) => ({
    addToCard: (itemId) => dispatch(addToCard(itemId)),
    removeFromCard: (itemId) => dispatch(removeFromCard(itemId))
});

export default connect(mapStateToProps, mapDispatchTiProps)(ShopItem);