import { combineReducers } from 'redux';
import panierReducer from "./panierReducer";
import downshiftReducer from './downshiftReducer'
// Combine all the reducers
const rootReducer = combineReducers({
    panierReducer,
    downshiftReducer
});
export default rootReducer;