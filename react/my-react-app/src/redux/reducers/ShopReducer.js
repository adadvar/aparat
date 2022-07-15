const ADD_TO_CARD = 'ADD-TO-CARD';
const REMOVE_FROM_CARD = 'REMOVE-FROM-CARD';
const REMOVE_FROM_CARD_APPLY = 'REMOVE-FROM-CARD-APPLY';

const addToCard = (itemId) => ({
    type: ADD_TO_CARD,
    itemId
});

const removeFromCard = (itemId) => {
    return (dispatch) => {
        setTimeout(() => {
            dispatch(removeFromCardApply(itemId));
        }, 3000);
    }
}

const removeFromCardApply = (itemId)=>{
    return {
        type: REMOVE_FROM_CARD_APPLY,
        itemId
    }
}

const initialState =  {
    items: [
        {id: 1, name: 'tv', price: 1500000},
        {id: 2, name: 'radio', price: 4500000},
        {id: 3, name: 'dvd', price: 3000000},
    ],
    card: [
    ]
}

const reducerAddtoCard = (state, action)  => ({
    ...state,
    card: state.card.lastIndexOf(action.itemId) > -1 
    ? state.card
    : [...state.card, action.itemId]
})

const reducerRemoveFromCard = (state, action) => ({
    ...state,
    card: state.card.filter(id => id !== action.itemId)
})

const reducer = (state = initialState, action) => {
    switch(action.type){
        case ADD_TO_CARD:
            return reducerAddtoCard(state, action);
        case REMOVE_FROM_CARD_APPLY:
            return reducerRemoveFromCard(state, action);
    }
    return state;
}

export {
    addToCard,
    removeFromCard
};
export default reducer;