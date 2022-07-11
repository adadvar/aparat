import { createStore } from 'redux';

const initState = {
    counter: 0
}
const reducer = (state = initState, action) => {
    console.log(action);
    if(action.type === 'INCREAMENT'){
        return {
            ...state, 
            counter: state.counter +1
        };
    }

    if(action.type === 'DECEREMENT'){
        return {
            ...state, 
            counter: state.counter -1
        };
    }
    return state;
}

const store = createStore(reducer);

console.log(store.getState());

const increamentAction = {type: 'INCREAMENT'};
store.dispatch(increamentAction);

console.log(store.getState());

const decreamenttAction = {type: 'DECEREMENT'};
store.dispatch(decreamenttAction);

console.log(store.getState());
