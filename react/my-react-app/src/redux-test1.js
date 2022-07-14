import { createStore } from 'redux';
import * as RTActions from './redux-test-actions3';

const initState = {
    counter: 0
}
const reducer = (state = initState, action) => {
    console.log(action);
    if(action.type === RTActions.INC_ACTION){
        return {
            ...state, 
            counter: state.counter +1
        };
    }

    if(action.type === RTActions.DEC_ACTION){
        return {
            ...state, 
            counter: state.counter -1
        };
    }

    if(action.type === RTActions.ADD_ACTION){
        return {
            ...state,
            counter: state.counter + action.value
        }
    }
    
    return state;
}

const store = createStore(reducer);

store.subscribe(() => {
    console.log(store.getState());
});

store.dispatch(RTActions.increamentAction());
store.dispatch(RTActions.decreamenttAction());
store.dispatch(RTActions.addAction(2));
