import React, {Component} from "react";
import axios from '../axios/base.js'
import './Login.css';


class Login extends Component {
    usernameInput = null;
    passwordInput = null;

    login = (data) => {
        axios.post('login', data)
                .then((response) => {
                    console.log(response); 
                })
                .catch((err) => {
                    console.log(err);
                })
    }

    onSubmitForm = (e) => {
        e.preventDefault();
        let username = this.usernameInput.value.trim();
        let password = this.passwordInput.value.trim();
        let data = {email: username, password};

        if(username.length >=1 && password.length >=1){ 
            this.login(data);

        }else {
            alert('اطلاعات واردشده معتبر نمیباشد');
        }
    }

    render(){
        return (
            <div className='Login'>
                <form>
                    <div className='input-box'>
                        <label>نام کاربری</label>
                        <input ref={el=>this.usernameInput=el} type='text' className='input-control' placeholder='نام کاربری خود را واردکنید'></input>
                    </div>
                    <div className='input-box'>
                        <label>گذرواژه</label>
                        <input ref={el=>this.passwordInput=el} type='text' className='input-control'  placeholder='گذرواژه خود را واردکنید'></input>
                    </div>

                    <div className='input-box'>
                        <button className='button' onClick={this.onSubmitForm}>ورود</button>
                    </div>
                </form>
            </div>
        );
    }
}

export default Login;