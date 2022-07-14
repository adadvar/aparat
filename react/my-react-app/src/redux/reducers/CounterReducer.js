const ADD_ACTION = 'ADD_ACTION';
const MINUS_ACTION = 'MINUS_ACTION';

const addAction = () => ({
    type: ADD_ACTION,
})

const minusAction = () => ({
    type: MINUS_ACTION,
})


const initialState = {
    count: 0
}

const reducer = (state = initialState, action) => {
    switch(action.type){
        case ADD_ACTION:
            return {
                ...state,
                count: state.count +1
            };

        case MINUS_ACTION:
            return {
                ...state,
                count: state.count > 1 ? state.count -1 : 0
            };
    }

    return state;
}


export {
    addAction,
    minusAction
}
export default reducer;