import { compose, createStore, applyMiddleware } from 'redux';
import createRootReducer from '../reducers';

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const store = createStore(
    createRootReducer(),
    composeEnhancers()
);

export default store;
