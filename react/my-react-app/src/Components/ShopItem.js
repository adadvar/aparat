import React, {Component} from "react"

class ShopItem extends Component {
    render(){
        return(
            <>
                <h3>{this.props.name}</h3>
                <div>
                    <b>{this.props.price}</b>

                    <button >add</button>
                </div>
            </>
        );
    }
}

export default ShopItem;