import { createStore } from 'redux';
import reducer, {addTodo, removeTodo} from './reducers/todoReducer';

const store = createStore(reducer);

export {
    addTodo,
    removeTodo
}
export default store;