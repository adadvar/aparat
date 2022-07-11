import React, {Component} from "react";
import Post from './Post.js';
import {useLocation} from 'react-router-dom';
const Blog = (props) => {

          const location = useLocation();
          console.log(location);

        return (
            <div className='blog'>
                <h1>Blog Posts</h1>
                {props.posts.map(post => (
                    <Post key={post.id} {...post}/>
                ))}
            </div>
        )
}

export default Blog;