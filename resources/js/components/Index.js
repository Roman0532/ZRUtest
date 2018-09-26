import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, Link, Route} from 'react-router-dom';
import Example from './Users';

export default class Index extends Component {
    render() {
        return (
            <div className="container w-100">
                <Router>
                    <div className="panel-body">
                        <Route path='/users' component={Example}/>
                    </div>
                </Router>
            </div>
        );
    }
}

if (document.getElementById('example')) {
    ReactDOM.render(<Index/>, document.getElementById('example'));
}