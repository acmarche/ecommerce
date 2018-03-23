export const UPDATE_QUANTITY = 'UPDATE_QUANTITY';
export const SETUP_INITIAL = 'SETUP_INITIAL';
export const CANCEL = 'CANCEL';
export const DELETE_ITEM = 'DELETE_ITEM';

/*

clickedElem :
{
    nom: "Philadelphia fines herbes"
    ​prix: 3.63
    quantite: 12
}
 */
export const updateQuantity = (evt,product) => {
    if (!isNumericPositive(evt.target.value)) {
        // alphabet letters found
        return{
            'type':CANCEL
        }
    }
    product.quantite = evt.target.value;
    return {
        'type':UPDATE_QUANTITY,
        'payload':product
    }
};

function isNumericPositive(n) {
    if(!isNaN(parseFloat(n)) && isFinite(n)){
        return n >= 0;
    }
    return false;
}

export const deleteItem = (item) => {
    return {
        'type':DELETE_ITEM,
        'payload':item
    }
};

export const setupInitial = (initialState) =>{
    //TODO -> call API for order total price
    let total = 12.50;
    return {
        'type':SETUP_INITIAL,
        'payload':initialState.map((item,index) => {
            return{
                ...item,
                total : total.toFixed(2) + "€",
                produits : item.produits.map((product,index) => {
                    return{
                        ...product,
                        prix:product.prix.toFixed(2) + "€"
                    }
                })
            }
        })
    }
};
