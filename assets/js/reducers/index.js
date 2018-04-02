import { combineReducers } from 'redux';
import {PENDING_UPDATE, SETUP_INITIAL, CANCEL, PENDING_DELETE,TOGGLE_EXPAND_ITEM_ATTRIBUTES,
    DELETE_ITEM, UPDATE_ORDER,HANDLE_CLOSE_MODAL_DELETE,HANDLE_SHOW_MODAL_DELETE} from "../actions/actionsPanier"

let dataState = { orders: [], loading:false,showModalDelete: false };

const panierReducer = (state = dataState, action) => {

    switch (action.type) {

        //  Will temporarily change the item quantity while the API is requested asynchronously(in action)
        // A circular progress is shown
        case PENDING_UPDATE:
            return {
                ...state,
                loading: true,
                orders:updateItem(state.orders,action.payload),
            };

        case PENDING_DELETE:
            return {
                ...state,
                loading: false,
                showModalDelete:false,
                orders:removeItem(updateItem(state.orders,action.payload),action.payload)
            };

        //The fetch request is done, update total and remove circular icon
        case UPDATE_ORDER:
            return {
                ...state,
                loading: false,
                orders:updateOrderTotal(state.orders,action.payload.newOrders, action.payload.idOrderToUpdate)
            };

        case SETUP_INITIAL:
            return {
                ...state,
                orders:action.payload.map((item) => {
                    return{
                        ...item,
                        produits:item.produits.map((produit) => {
                            return{
                                ...produit,
                                expanded:false
                            }
                        })
                    }
                })
            };

        case DELETE_ITEM:
            return {
                ...state,
                orders:removeItem(state.orders,state.itemPendingDelete),
                showModalDelete:false,
            };

        case HANDLE_CLOSE_MODAL_DELETE:
            return {
                ...state,
                showModalDelete: false
            };

        case HANDLE_SHOW_MODAL_DELETE:
            return{
                ...state,
                showModalDelete: true,
                itemPendingDelete: action.payload
            };

        case CANCEL:
            return state;

        case TOGGLE_EXPAND_ITEM_ATTRIBUTES:
            return {
                ...state,
                orders:state.orders.map((item) => {
                    return{
                        ...item,
                        produits:item.produits.map((produit) => {
                            if(produit.id === action.payload.id){
                                return{
                                    ...produit,
                                    expanded: !produit.expanded
                                }
                            }
                            return{
                                ...produit,
                            }
                        })
                    }
                })
            };

        default:
            return state;
    }
};

function updateItem(array,itemToUpdate){
    return array.map((item, index) => {
        return {
            ...item,
            produits:updateArray(item.produits,itemToUpdate,item)
        };
    });

    function updateArray(array,newItem,order) {
        return array.map((item, index) => {
            if(item.id !== newItem.id) {
                // This isn't the item we care about - keep it as-is
                return item;
            }

            // Otherwise, this is the one we want
            // set the circular progress of the order visible
            order.showCircularProgress = true;
            // and return an updated value
            return {
                ...item,
                quantite:newItem.quantite
            };
        })
    }
}

function updateOrderTotal(orders, newOrders, idOrderToUpdate){
    if(idOrderToUpdate === undefined){ //Update all orders
        return newOrders;
    }
    return orders.map((order) => {    //Only update order with ID = idOrderToUpdate
        if(order.id === idOrderToUpdate){
            order.showCircularProgress = false;
            order.cout = newOrders.filter(item => item.id === idOrderToUpdate)[0].cout;
        }
        return order;
    })
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