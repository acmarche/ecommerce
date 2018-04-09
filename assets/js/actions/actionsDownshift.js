import keycode from 'keycode'
import {onItemsUpdated} from "./actionsPanier";
import store from "../store/store";
export const HANDLE_KEY_DOWN = 'HANDLE_KEY_DOWN';
export const HANDLE_CHANGE = 'HANDLE_CHANGE';
export const HANDLE_CHANGE_INPUT = 'HANDLE_CHANGE_INPUT';
export const HANDLE_DELETE = 'HANDLE_DELETE';
export const ADD_TO_REDUCER = 'ADD_TO_REDUCER';

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

export const handleChange = (key,attribute,order) =>{
    let params = {
        'commandeProduit': order.id,
        'attributId': attribute.id
    };
    let request = prepareRequest(params,"POST");
    fetch('http://localhost:8000/panier/addAttributJSON',request)
        .then ((response) => response.json())
        .catch((error) => {
            console.error("Error with FETCH : " + error);
        });

    return{
        type:HANDLE_CHANGE,
        item:attribute,
        componentKey: key,
    }
};

export const handleDelete = (key,item,commandeProduit) =>{
    let params = {
        'commandeProduit': commandeProduit.id,
        'attributId': item.id
    };
    let request = prepareRequest(params,"DELETE");
    fetch('http://localhost:8000/panier/deleteAttributJSON',request)
        .then ((response) => response.json())
        .catch((error) => {
            console.error("Error with FETCH : " + error);
        });
    return{
        type:HANDLE_DELETE,
        item:item,
        componentKey: key,
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
