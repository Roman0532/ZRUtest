import React, {Component} from 'react';
import axios from 'axios'
import {Button} from "react-bootstrap";
import {Modal} from "react-bootstrap";

export default class Example extends Component {

    constructor(props) {
        super(props);
        this.state = {
            form: {
                fromUserValue: '',
                toUserValue: '',
            },
            isOpened: false,
            users: [],
            amount: 0,
            saveChanges: false
        };

        this.handleChangeFromUsers = this.handleChangeFromUsers.bind(this);
        this.handleChangeToUsers = this.handleChangeToUsers.bind(this);
        this.handleSubmitB = this.handleSubmitB.bind(this);
        this.handleSubmitB1 = this.handleSubmitB1.bind(this);
        this.handleChangeAmount = this.handleChangeAmount.bind(this);
    }

    componentDidMount() {
        axios.get('api/users')
            .then(res => {
                const users = res.data;
                this.setState({users});
            });
    }

    handleChangeAmount(event) {
        event.preventDefault();
        this.setState({amount: event.target.value});
    }

    handleSubmit(state) {
        console.log(state);
    };

    // handleSubmitB(event) {
    //     event.preventDefault();
    //     this.setState({isOpened: !this.state.isOpened, amount: 0})
    // }
    //
    //
    // handleSubmitB1(event) {
    //     event.preventDefault();
    //     if (this.state.amount === '') {
    //         this.setState({amount: 0})
    //     }

    //     this.setState({isOpened: !this.state.isOpened, saveChanges: true})
    // }

    handleChangeFromUsers(event) {
        this.setState({form: {fromUserValue: event.target.value}});
    }

    handleChangeToUsers(event) {
        this.setState({form: {toUserValue: event.target.value}});
    }

    renderUsers() {
        const {users} = this.state;
        return users.map(user => <option key={user.id} value={user.id}> {user.first_name}</option>)
    }

    renderModal() {
        return (
            <div className="static-modal">
                <Modal.Dialog>
                    <Modal.Header>
                        <Modal.Title>Modal title</Modal.Title>
                    </Modal.Header>

                    <Modal.Body>
                        <form>
                            <input type="number" onChange={this.handleChangeAmount} value={this.state.amount}/>Введите
                            сумму перевода
                        </form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button onClick={this.handleSubmitB}>Close</Button>
                        <Button onClick={this.handleSubmitB1} bsStyle="primary">Save changes</Button>
                    </Modal.Footer>
                </Modal.Dialog>
            </div>
        );
    }

    render() {
        return (
            <div className="container">
                <form onSubmit={this.handleSubmit(this.state)}>
                    <div className="form-group">
                        <label htmlFor="exampleFormControlSelect1">От кого отравить:</label>
                        <select onChange={this.handleChangeFromUsers} value={this.state.form.fromUserValue}
                                className="form-control" id="exampleFormControlSelect1">
                            {this.renderUsers()}
                        </select>
                    </div>

                    <div className="form-group">

                        <label htmlFor="exampleFormControlSelect1">Кому отправить</label>

                        <select onChange={this.handleChangeToUsers} value={this.state.form.toUserValue}
                                className="form-control" id="exampleFormControlSelect1">
                            {this.renderUsers()}
                        </select>
                    </div>
                    <button type="button" onClick={this.handleSubmitB} className="btn btn-primary" data-toggle="modal"
                            data-target="#exampleModalCenter">
                        Ввести сумма перевода
                    </button>

                    Сумма перевода : {this.state.amount}

                    {this.state.saveChanges ? <button type="submit" className="btn btn-success">Submit</button> : null}
                </form>
                {this.state.isOpened ? this.renderModal() : null}
            </div>
        );
    }
}