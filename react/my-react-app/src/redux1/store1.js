import * as Acts from './store1-actions';

const initState = {
    users:[
        {id:1, name: 'alireza', family: 'dadvar'}
    ],
    auth: null
};

const reducer = (state = initState, action) => {
    switch(action.type){
        case Acts.ADD_USER:
            return {
                ...state,
                users: [...state.users, action.user]
            }
    }
    return state;
};

export default reducer;