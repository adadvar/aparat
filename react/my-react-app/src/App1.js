import React, {Component} from 'react';
import User from './Components/User';

class App extends Component{
  state = {
   users :[
       {id : 1 ,name: "alireza", age: "28" },
       {id : 2 ,name: "foo", age: "12" },
       {id : 3 ,name: "bar", age: "23" },
   ],
   searchText : null,
 }

 showList= () => {
  let users = this.state.searchText ? this.state.users.filter(user => {
    let regex = new RegExp(this.state.searchText);
    return user.name.match(regex);
  }) : this.state.users;
  return (
    <div>
      {
        users.map(user => (
          <User
            key={user.id}
            name={user.name}
            age={user.age}
            id={user.id}
            onRemove = {this.removePerson}
          />
        ))
      }
    </div>
  )
 }

 addPerson = () => {
  let name1 = prompt('enter name: ');
  let age1 = prompt('enter age: ');

  let newState = {...this.state};
  let newId = 1;
  if(newState.users.length){
    newId = 1+ newState.users[newState.users.length -1].id;
  }
  let user = {id:newId, name: name1, age:age1};

  // newState.users.push(user);
  newState.users = [...newState.users, user];

  this.setState(newState);
 }

 removePerson = (id) => {
  let newState = {...this.state};
  newState.users = newState.users.filter(user => user.id != id);
  this.setState(newState);
 }

 filterUsers = (e) => {
  let newState = {...this.state};
  newState.searchText = e.target.value;
  this.setState(newState);
}

  render(){
    return (
      <div>
        <button onClick={this.addPerson}>Add Person</button>

        <input className='search' type="text" placeholder='enter a name to search' onChange={this.filterUsers}/>

        <br/>
        <br/>
        {this.showList()}
      </div>
    );
  }
}



export default App;