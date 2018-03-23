import { combineReducers } from 'redux';
import {UPDATE_QUANTITY, SETUP_INITIAL,CANCEL,DELETE_ITEM} from "../actions/actionsPanier"

let dataState = { products: [], loading:true };

const panierReducer = (state = dataState, action) => {

    switch (action.type) {
        case UPDATE_QUANTITY:
            return {
                ...state,
                products:updateItem(state.products,action.payload),
            };

        case SETUP_INITIAL:
            return {
                ...state,
                products:action.payload
            };

        case DELETE_ITEM:
            return {
                ...state,
                products:removeItem(state.products,action.payload),
            };


        case CANCEL:
            return state;

        default:
            return state;
    }
};

function updateItem(array,itemToUpdate){
    return array.map((item, index) => {
        return {
            ...item,
            produits:updateArray(item.produits,itemToUpdate)
        };
    });

    function updateArray(array,newItem) {
        return array.map((item, index) => {
            if(item.id !== newItem.id) {
                // This isn't the item we care about - keep it as-is
                return item;
            }

            // Otherwise, this is the one we want - return an updated value
            return {
                ...item,
                quantite:newItem.quantite
            };
        })
    }
}

function removeItem(array, itemToRemove) {

    //Remove itemToRemove
    return array.map((item,index) =>{
        return {
            ...item,
            produits : item.produits.filter((item, index) => item.id !== itemToRemove.id)
        }
    //Purge array from shops with no orders
    }).filter((item,index) => item.produits.length > 0);



}

// Combine all the reducers
const rootReducer = panierReducer;
export default rootReducer;