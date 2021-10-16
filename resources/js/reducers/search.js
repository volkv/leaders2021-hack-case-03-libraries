import { handleActions } from 'redux-actions';

const initialState = '';

const searchReducer = handleActions({
    set: (state, { payload }) => payload
}, initialState);

export default searchReducer;