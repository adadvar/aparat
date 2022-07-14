import { createStore } from 'redux';
import *  as Acts  from './redux-test-actions3';

const initState = {
    todo: []
};

const reducer = (state = initState, action) => {
    if(action.type === Acts.ADD_ACTION){
        return {
            ...state,
            todo: [...state.todo, action.item]
        };
    }

    if(action.type === Acts.REMOVE_ACTION){
        return {
            ...state,
            todo: state.todo.filter(item => item.title != action.title)
        };
    }

    return state;
}

const store = createStore(reducer);

store.subscribe(()=>{
    console.log(store.getState());
});

store.dispatch(Acts.addAction({title: 'todo 1', body: 'body1'}));
store.dispatch(Acts.addAction({title: 'todo 2', body: 'body2'}));
store.dispatch(Acts.addAction({title: 'todo 3', body: 'body3'}));


store.dispatch(Acts.removeAction('todo 2'));