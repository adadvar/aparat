import {createStore} from 'redux';
import * as Act from './redux-test-actions3';

const initState = {
    student: []
};

const reducer = (state = initState, action) => {
    if(action.type === Act.ADD_ST){
            // console.log(action.item);
            const newSt = {
                id: state.student[state.student.length-1].id +1,
                name: action.item.name,
                score: action.item.score,
            }
            return {
                ...state,
                student: [...state.student, newSt]
            };
        }

    if(action.type === Act.REMOVE_ST){
        // console.log(action.item);
        return {
            ...state,
            student: state.student.filter(item => item.id != action.id  )
        };
    }
    return state;
}

const store = createStore(reducer);

store.subscribe (() => {
    console.log(store.getState());
});

store.dispatch(Act.addSt({name: 'alireza', score: 20}));
store.dispatch(Act.addSt({name: 'ali', score: 20}));
store.dispatch(Act.addSt({name: 'reza', score: 20}));



store.dispatch(Act.removeSt(2));


