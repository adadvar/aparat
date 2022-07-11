import React, {Component} from "react";

class PostInsert extends Component {
    titleInput = null;
    bodyInput = null;
    onPostCreate = (e) => {
        e.preventDefault();
        let post= 
            {
                title: this.titleInput.value.trim(),
                body: this.bodyInput.value.trim(),
            };

        if(post.title.length && post.body.length){
            this.titleInput.value = '';
            this.bodyInput.value = '';
    
            this.props.onPostCreate(post);
        }else {
            alert('plz fill input');
        }

    }
    render(){
        return (
            <div className="post-insert">
                <form onSubmit={this.onPostCreate}>
                    <div>
                         {/* <input name="title" placeholder="post title" /> */}
                         <input ref={el => this.titleInput = el} name="title" placeholder="post title" />
                    </div>
                    <div>
                         {/* <textarea name="body" placeholder="post body" /> */}
                         <textarea ref={el => this.bodyInput = el} name="body" placeholder="post body" />
                    </div>

                    <button type="submit">create</button>
                </form>
            </div>

        )
    }
}

export default PostInsert;