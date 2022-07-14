export const ADD_ACTION = 'ADD';
export const INC_ACTION = 'INCREMENT';
export const DEC_ACTION = 'DECREMENT';

export const addAction = (value) => ({type: ADD_ACTION, value}); 
export const increamentAction = ()=>({type: INC_ACTION});
export const decreamenttAction = ()=>({type: DEC_ACTION});
