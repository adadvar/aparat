export const ADD_ACTION = 'ADD';
export const REMOVE_ACTION = 'REMOVE';

export const addAction = (item) => ({type: ADD_ACTION, item});
export const removeAction = (title) => ({type: REMOVE_ACTION, title});