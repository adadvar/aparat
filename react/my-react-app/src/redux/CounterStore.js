import {createStore} from 'redux';
import reducer  from './reducers/CounterReducer';

const store = createStore(reducer);

export default store;