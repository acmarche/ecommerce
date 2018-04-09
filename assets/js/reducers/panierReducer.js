import {PENDING_UPDATE, SETUP_INITIAL, CANCEL, PENDING_DELETE,TOGGLE_EXPAND_ITEM_ATTRIBUTES,
    DELETE_ITEM, UPDATE_ORDER,HANDLE_CLOSE_MODAL_DELETE,HANDLE_SHOW_MODAL_DELETE,
    DELETE_ATTRIBUT,PENDING_DELETE_ATTRIBUT} from "../actions/actionsPanier"

let dataState = {
    orders: [],
    loading:false,
    showModalDelete: false,
    totalWithStripe:0,
    grandTotal:0
};

export default function panierReducer(state = dataState, action){

    switch (action.type) {

        //  Will temporarily change the item quantity while the API is requested asynchronously(in action)
        // A circular progress is shown
        case PENDING_UPDATE:
            return {
                ...state,
                loading: true,
                orders:updateItem(state.orders,action.payload,true),
            };

        case PENDING_DELETE:
            return {
                ...state,
                loading: true,
                showModalDelete:false,
                orders:removeItem(updateItem(state.orders,action.payload,true),action.payload)
            };

        case PENDING_DELETE_ATTRIBUT:
            return {
                ...state,
                loading:true,
                orders:removeAttribute(state.orders,action.payload.attribute,true)
            };

        //The fetch request is done, update total and remove circular icon
        case UPDATE_ORDER:
            return {
                ...state,
                loading: false,
                orders:updateOrderTotal(state.orders,action.payload.newOrders, action.payload.idOrderToUpdate),
                totalWithStripe:computeStripeTotal(state),
                grandTotal:computeGrandTotal(state)
            };

        case SETUP_INITIAL:
            return {
                ...state,
                totalWithStripe: computeStripeTotal({orders:action.payload}),
                grandTotal: computeGrandTotal({orders:action.payload}),
                orders:action.payload.map((order) => {
                    return{
                        ...order,
                        commandeProduits:order.commandeProduits.map((produit) => {
                            return{
                                ...produit,
                                expanded:false,
                            }
                        })
                    }
                }),
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
                        commandeProduits:item.commandeProduits.map((commandeProduit) => {
                            if(commandeProduit.id === action.payload.id && commandeProduit.produit.produitListingAttributs.length > 0){
                                return{
                                    ...commandeProduit,
                                    expanded: !commandeProduit.expanded
                                }
                            }
                            return{
                                ...commandeProduit,
                            }
                        })
                    }
                })
            };

        default:
            return state;
    }
};

function updateItem(array,itemToUpdate,showCircularProgress){
    return array.map((item, index) => {
        return {
            ...item,
            commandeProduits:updateArray(item.commandeProduits,itemToUpdate,item)
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
            order.showCircularProgress = showCircularProgress;
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
            commandeProduits : item.commandeProduits.filter((item, index) => item.id !== itemToRemove.id)
        }
        //Purge array from shops with no orders
    }).filter((item,index) => item.commandeProduits.length > 0);
}

function removeAttribute(orders, attributeToRemove, showCircularProgress){
    return orders.map((order) => {
        return {
            ...order,
            commandeProduits:order.commandeProduits.map((produit) => {
                return{
                    ...produit,
                    attributs:commandeProduits.attributs.filter((attribute) => attribute.id !== attributeToRemove.id)
                }
            })
        };
    });
}

function computeGrandTotal(state){
    let grandTotal = 0;
    state.orders.map((order) => {
        grandTotal += order.cout.totalTvac;
        return order;
    });
    return grandTotal + computeStripeTotal(state);
}

function computeStripeTotal(state){
    let totalFraisTransaction = 0;
    state.orders.map((order) => {
        totalFraisTransaction += order.cout.fraisTransaction;
    });
    return totalFraisTransaction;
}
