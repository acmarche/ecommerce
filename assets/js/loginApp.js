import React from 'react';
import ReactDOM from 'react-dom';
import LoginForm from "./components/login";
import store from './store/store'; //Import the store
import { Provider } from 'react-redux';

var errorObject = document.currentScript.getAttribute('error');
ReactDOM.render(
    <LoginForm error={errorObject}/>,
    document.getElementById('login')
)