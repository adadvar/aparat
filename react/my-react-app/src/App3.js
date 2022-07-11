import React, { Component } from 'react';
import { BrowserRouter, Navigate, Routes, Route , useLocation } from 'react-router-dom';
import axios from 'axios';
import NewPost from './Components/NewPost';
import Blog from './Components/Blog';
import NavBar from './Components/NavBar';
import './App.css'

class App extends Component { 
  state = {
    posts: []
  }

  addNewPost = (post) => {

    this.setState((oldState, props) => (
      {
        ...oldState,
        posts: [
          ...oldState.posts,
          {
            id: oldState.posts[oldState.posts.length - 1].id + 1,
            ...post,
          }
        ]
      }
    ));
  }

  render() {
    return (
      
      <BrowserRouter>
      <NavBar />
        <Routes>
          {/* <Route path="/" element={<NavBar/>} /> */}
          <Route path="/new-post" element={<NewPost onPostcreated={this.addNewPost} />} />
          <Route path="/blog" element={<Blog  posts={this.state.posts} />} />
          <Route path='/blog:id' element={<SinglePost />} />
        </Routes>
      </BrowserRouter>
    );
  }

  componentDidMount(){
    axios.get('https://jsonplaceholder.typicode.com/posts')
        .then((response) => {
          
          let data = response.data.slice(0,5).map(item => (
            {
              id: item.id,
              title: item.title,
              body: item.body,
            }
            ));
          this.setState((oldState, props) => {
            return {
              ...oldState,
              posts:data
            };
            });
        })
        .catch(function(err){
          alert(err);
          console.log(err);
        });
  }
}



export default App;