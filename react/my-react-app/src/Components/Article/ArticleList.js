import React, {Component} from "react";
import axios from '../axios/base';

class ArticleList extends Component {

    getAcrticles = () => {
        axios.get('articles')
        .then(response => {
            console.log(response);
        })
        .catch(err => {
            console.log(err);
        })
    }

    renderArticles = () => {
        this.getAcrticles();
    }
    
    render(){
        return (
            <div className="Article-List">
                {this.renderArticles()}
            </div>
        );
    }
}

export default ArticleList;