import React, {Component} from "react";
import {connect} from 'react-redux';

class Card extends Component{

    calcTotalPrice = () => {
        let result = this.props.card.map(itemId => {
            let item = this.props.items.filter(item => item.id === itemId)[0];
            return item.price;
        });

        return result.reduce((total, price) => total+price,0);
    }

    render(){
        return (
            <>
                <i>item count : {this.props.card.length}</i>
                <b>total price:{this.calcTotalPrice()}</b>
            </>
        )
    }
}


const mapStateToProps = (state) => ({
    card: state.card,
    items: state.items
})

export default connect(mapStateToProps)(Card);