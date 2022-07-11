import React, { Component } from "react";


class NewPost extends Component {
    titleInput = null;
    bodyInput = null;

    handleAddButtonClick = () => {
        let post = {
            title: this.titleInput.value.trim(),
            body: this.bodyInput.value.trim(), 
        };

        if(post.title.length && post.body.length){
            this.titleInput.value = '';
            this.bodyInput.value = '';
            this.props.onPostcreated(post);
        }else {
            alert('plz fill input');
        }
    }


    render(){
        return (
            <div className='new-post'>
                <h1>Add New Post</h1>
                <input ref={el => this.titleInput = el} placeholder='enter the post title'/>
                <textarea ref={el => this.bodyInput = el}  placeholder='enter the post body'/>

                <button onClick={this.handleAddButtonClick}>Add new post</button>
            </div>
        )
    }
}

export default NewPost;