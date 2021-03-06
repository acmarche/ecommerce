import React from 'react';
import ReactDOM from 'react-dom';
import PanierDetails from "./components/panier";
import store from './store/store'; //Import the store
import { Provider } from 'react-redux';

var panierJSON = document.currentScript.getAttribute('panier');
var panierObject = JSON.parse(panierJSON);

var token = document.currentScript.getAttribute('token');

ReactDOM.render(
    <Provider store={store}>
        <PanierDetails panier={panierObject} token={token}/>
    </Provider>,
    document.getElementById('detailpanier')
);