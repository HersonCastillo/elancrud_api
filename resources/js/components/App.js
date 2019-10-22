import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Switch, Route, Router } from 'react-router-dom'
import Header from './elements/Header';
import axios from 'axios';

import Home from './views/Home';
import Products from './views/Products';

export default class App extends Component {
    componentDidMount(){
        
    }
    render() {
        return (
            <BrowserRouter>
                <div>
                    <Header/>
                </div>
                <Switch>
                    <Route path='/'><Home/></Route>
                    <Route path='/products'><Products/></Route>
                </Switch>
            </BrowserRouter>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
