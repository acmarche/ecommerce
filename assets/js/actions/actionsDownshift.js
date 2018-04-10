import keycode from 'keycode'
import {whenItemsUpdated} from "./actionsPanier";
import store from "../store/store";
export const HANDLE_KEY_DOWN = 'HANDLE_KEY_DOWN';
export const HANDLE_CHANGE = 'HANDLE_CHANGE';
export const HANDLE_CHANGE_INPUT = 'HANDLE_CHANGE_INPUT';
export const HANDLE_DELETE = 'HANDLE_DELETE';

export const ADD_TO_REDUCER = 'ADD_TO_REDUCER';

import {PENDING_DELETE_ATTRIBUT,PENDING_ADD_ATTRIBUT,CANCEL} from "../actions/actionsPanier"

export const handleKeyDown = (key,evt) => {
    return{
        type:HANDLE_KEY_DOWN,
        keycode: keycode(evt),
        componentKey: key,
    }
};

export const handleInputChange = (key,event) => {
    return{
        type:HANDLE_CHANGE_INPUT,
        newInput: event.target.value,
        componentKey: key,
    }
};

export const handleChange = (key,attribute,commandeProduit) =>{
    return (dispatch) => {
        if(store.getState().
            downshiftReducer.componentStates
                .filter((component) => component.id === key)[0]
                    .selectedItem.some((attributeCheck) => attribute.id === attributeCheck.id)){
            dispatch(cancel());
            console.log("Canceled,fuck off");
        }
        else
        {
            let params = {
                'commandeProduit': commandeProduit.id,
                'attributId': attribute.id
            };
            let request = prepareRequest(params,"POST");
            fetch('http://localhost:8000/panier/addAttributJSON',request)
                .then ((response) => response.json())
                .then((json) => dispatch(whenItemsUpdated(JSON.parse(json.orders),commandeProduit.idCommande)))
                .catch((error) => {
                    console.error("Error with FETCH : " + error);
                });

            dispatch(addAttribute(key,attribute));
            dispatch(whileAddingAttribute(commandeProduit,attribute))
        }
    }
};

export const cancel= () => {
    return{
        type: CANCEL
    }
};

export const handleDelete = (key,attribut,commandeProduit) =>{
    return(dispatch) => {
        let params = {
            'commandeProduit': commandeProduit.id,
            'attributId': attribut.id
        };
        let request = prepareRequest(params,"DELETE");
        fetch('http://localhost:8000/panier/deleteAttributJSON',request)
            .then ((response) => response.json())
            .then((json) => dispatch(whenItemsUpdated(JSON.parse(json.orders),commandeProduit.idCommande)))
            .catch((error) => {
                console.error("Error with FETCH : " + error);
            });
        dispatch(deleteAttribute(key,attribut));
        dispatch(whileDeletingAttribute(commandeProduit,attribut))
    }
};

export const addAttribute = (key,attribute) => {
    return{
        type:HANDLE_CHANGE,
        item:attribute,
        componentKey: key,
    }
};

export const deleteAttribute = (key,item) => {
    return{
        type:HANDLE_DELETE,
        attribute:item,
        componentKey:key,
    }
};

export const whileDeletingAttribute = (target,attribute) => {
    return{
        type:PENDING_DELETE_ATTRIBUT,
        attribute:attribute,
        produitTarget:target,
    }
};

export const whileAddingAttribute = (target,item) => {
    return {
        type:PENDING_ADD_ATTRIBUT,
        attribute:item,
        produitTarget:target,
    }
};

export const addToReducer = (key,selection) => {
    return{
        type: ADD_TO_REDUCER,
        componentKey: key,
        selection: selection,
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
