import React,{ Component } from  "react";
import {Link, NavLink} from 'react-router-dom'

class NavBar extends Component {
    render(){
        return (
            <header>
                <nav className="navigation">
                    <ul>
                        <li><NavLink to="/new-post">New Post</NavLink></li>
                        <li><NavLink to="/blog">Blog</NavLink></li>
                    </ul>
                </nav>
            </header>
        );
    }
}

export default NavBar;