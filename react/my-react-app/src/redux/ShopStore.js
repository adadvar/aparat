import { createStore } from "redux";
import reducer from "./reducers/ShopReducer";

const store = createStore(reducer);

export default store;