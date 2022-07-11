import React, {Component} from 'react';
import PostInsert from './Components/PostInsert';

class App extends Component{
  state = {
    title: 'my project title',
    posts: [
      {id: 1, title: 'post 1', body: 'post1 body'},
    ]
  }

  addNewPost = (post) => {
    this.setState((oldState, props) => {
      let id = oldState.posts[oldState.posts.length - 1].id + 1;
      let newState = {
        ...oldState,
        posts: [...oldState.posts, {id, ...post}]
      };

      return newState;
    });
  }

  render(){
    return (
      <div>
         <h1>{this.state.title}</h1>

        <PostInsert onPostCreate={this.addNewPost}/>

         <div className='post-panel'>
          <h2>my posts:</h2>

          {this.state.posts.map(
            post => (
              <div className='post' key={post.id}>
                <h3>{post.title}</h3>
                <p>{post.body}</p>
              </div>
            )
          )}
         </div>
      </div>
    );
  }
}



export default App;