import React, {Component} from "react";
import {connect} from 'react-redux';
import ShopItem from './ShopItem';

class Shop extends Component {
 
    renderItems = () => {
        return this.props.items.map(item => (
            <ShopItem key={item.id}{...item}/>
        ));
    }

    render(){
        console.log(this.props);
        return (
            <>
                <Card />
                {this.renderItems()}
            </>
        );
    }
}

const mapStateToProps = (state) => ({
    items: state.items
})

export default connect(mapStateToProps)(Shop);