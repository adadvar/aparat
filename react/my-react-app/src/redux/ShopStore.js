import { createStore , applyMiddleware, compose} from "redux";
import thunk from "redux-thunk";
import reducer from "./reducers/ShopReducer";
import { addToCard, removeFromCard} from "./reducers/ShopReducer";

const logger = store => {
    return next => {
        return action => {
            const result= next(action);
            console.log(action);
            return result;
        }
    }
}

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const store = createStore(reducer, composeEnhancers(
    // applyMiddleware(logger, thunk)
    applyMiddleware(thunk)
));

export {
    addToCard,
    removeFromCard
};

export default store;