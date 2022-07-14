export const ADD_ST = 'ADD';
export const REMOVE_ST = 'REMOVE';

export const addSt = (item) => ({type: ADD_ST, item});
export const removeSt = (id) => ({type: REMOVE_ST, id});