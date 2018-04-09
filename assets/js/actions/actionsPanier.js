import store from '../store/store'; //Import the store

export const PENDING_UPDATE = 'PENDING_UPDATE';
export const PENDING_DELETE = 'PENDING_DELETE';
export const SETUP_INITIAL = 'SETUP_INITIAL';
export const CANCEL = 'CANCEL';
export const DELETE_ITEM = 'DELETE_ITEM';
export const SHOW_CIRCULAR_PROGRESS = 'CIRCULAR';
export const UPDATE_ORDER = 'UPDATE_ORDER';
export const HANDLE_CLOSE_MODAL_DELETE = 'HANDLE_CLOSE_MODAL_DELETE';
export const HANDLE_SHOW_MODAL_DELETE = 'HANDLE_SHOW_MODAL_DELETE';
export const TOGGLE_EXPAND_ITEM_ATTRIBUTES = 'TOGGLE_EXPAND_ITEM_ATTRIBUTES';
export const DELETE_ATTRIBUT = 'DELETE_ATTRIBUT';
export const PENDING_DELETE_ATTRIBUT = 'PENDING_DELETE_ATTRIBUT';

export const DOWNSHIFT_KEYDOWN = 'DOWNSHIFT_KEYDOWN';

/*

product :
{
    id: 3,
    nom: "Américain (pur boeuf)",
    quantite: 10,
    prixTvac: 2.78,
    idCommande: 5
}
 */
export const updateQuantity = (evt,product) => {
    return(dispatch) =>{
        let newQuantity = evt.target.value;
        if (!isNumericPositive(newQuantity) || store.getState().panierReducer.loading || newQuantity <= 0) {
            // alphabet letters found or currently loading
            return{
                'type':CANCEL
            }
        }

        let params = {
            idCommandeProduit: product.id,
            quantiteProduit:newQuantity,
        };

        let request = prepareRequest(params,"POST");
        fetch('http://localhost:8000/panier/updateJSON/',request)
            .then ((response) => response.json())
            .then ((json) => dispatch(onItemsUpdated(JSON.parse(json.orders),product.idCommande)))
            .catch((error) => {
                console.error("Error with FETCH : " + error);
            });

        product.quantite = newQuantity;
        dispatch(whileFetchingUpdate(product));
    }
};

export const whileFetchingUpdate = (product) =>{
    return {
        'type':PENDING_UPDATE,
        'payload':product
    }
};

export const whileFetchingDelete = (product) =>{
    return {
        'type':PENDING_DELETE,
        'payload':product
    }
};

export const whileFetchingDeleteAttribute = (product,attribute) => {
    return{
        type:PENDING_DELETE_ATTRIBUT,
        payload:{
            product:product,
            attribute:attribute
        }
    }
};

// Orders = the new state of all the orders sent by the API
// Product = the product for which the quantity has been updated
export const onItemsUpdated = (orders,idCommande) =>{
    return {
        'type':UPDATE_ORDER,
        'payload':{
            newOrders:orders,
            idOrderToUpdate:idCommande
        }
    }
};

function isNumericPositive(n) {
    if(!isNaN(parseFloat(n)) && isFinite(n)){
        return n >= 0;
    }
    return false;
}

export const deletePendingItem = () => {

    return(dispatch) =>{
        let product = store.getState().panierReducer.itemPendingDelete;
        let params = {
            'commandeProduit': product.id
        };

        let request = prepareRequest(params,"DELETE");
        fetch('http://localhost:8000/panier/deleteJSON',request)
            .then ((response) => response.json())
            .then ((json) => dispatch(onItemsUpdated(JSON.parse(json.orders),product.idCommande)))
            .catch((error) => {
                console.error("Error with FETCH : " + error);
            });
        dispatch(whileFetchingDelete(product))
    }
};

function prepareRequest(params,method) {
    let esc = encodeURIComponent;
    let body = Object.keys(params)
        .map(k => esc(k) + '=' + esc(params[k]))
        .join('&');

    let request = {
        method: method,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        credentials: 'include',
        body: body
    };
    return request;
}

export const deleteAttribut = (product,attribute) => {
    return(dispatch) =>{

        let params = {
            commandeProduit: product.id,
            attributId: attribute.id,
        };
        let request = prepareRequest(params,"DELETE");

        console.log("started fetch");
        fetch('http://localhost:8000/panier/deleteAttributJSON',request)
            .then ((response) => response.json())
            .then ((json) => dispatch(onItemsUpdated(JSON.parse(json.orders),product.idCommande)))
            .catch((error) => {
                console.error("Error with FETCH : " + error);
            });

        dispatch(whileFetchingDeleteAttribute(product,attribute));
    }
};

export const setupInitial = (initialState) =>{
    return {
        'type':SETUP_INITIAL,
        'payload':initialState.map((item,index) => {
            return{
                ...item,
                commandeProduits : item.commandeProduits.map((product,index) => {
                    let totalProduit = product.prixTvac;
                    return{
                        ...product,
                        prix:totalProduit
                    }
                })
            }
        })
    }
};

export const handleCloseModalDelete = () =>{
    return{
        type:HANDLE_CLOSE_MODAL_DELETE
    }
};

export const handleShowModalDelete = (itemPendingDelete) =>{
    return {
        type:HANDLE_SHOW_MODAL_DELETE,
        payload:itemPendingDelete
    }
};


export const toggleExpandItemAttributes = (product) =>{
    return{
        type:TOGGLE_EXPAND_ITEM_ATTRIBUTES,
        payload:product
    }
};

