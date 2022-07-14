import {createStore, combineReducers} from 'redux';
import reducer1 from "./store1";
import reducer2 from "./store2";
import * as Acts1 from './store1-actions';
import * as Acts2 from './store2-actions';

const reducer = combineReducers({
    r1: reducer1,
    r2: reducer2,
})
const store = createStore(reducer);

store.subscribe(() => {
    console.log(store.getState());
})

store.dispatch(Acts1.addUser({id:2, name: 'ali', family: 'rezaee'}));

store.dispatch(Acts2.addproduct({id:3, title: 'product3', price: 35000}));