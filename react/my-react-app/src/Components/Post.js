import React, { Component } from "react";
import { matchRoutes, useLocation, Link } from "react-router-dom"

const Post = (props) => {

    const location = useLocation();

    return (
        <div key={props.id} className='post'>
            <Link to={{
                pathname: location.pathname + "/" + props.id
            }}>
                <h3>{props.title}</h3>
            </Link>
            <p>{props.body}</p>
        </div>
    )
}

export default Post;