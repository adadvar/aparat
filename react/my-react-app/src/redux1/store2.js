import * as Acts from './store2-actions';

const initState = {
    products:[
        {id:1, title: 'product1', price: 45000},
        {id:2, title: 'product2', price: 25000},
    ]
};

const reducer = (state = initState, action) => {
    switch(action.type){
        case Acts.ADD_PRODUCT:
            return {
                ...state,
                products: [...state.products, action.product]
            }
    }
    return state;
};

export default reducer;
