import {HANDLE_KEY_DOWN,HANDLE_CHANGE_INPUT,HANDLE_CHANGE,HANDLE_DELETE,
ADD_TO_REDUCER} from "../actions/actionsDownshift"

let initialState={
    componentStates:[],
};

/*
ComponentState:
    id:1,
    inputValue:'',
    selectedItem:[]
*/

export default function downshiftReducer(state = initialState, action){
    switch(action.type){
        case HANDLE_KEY_DOWN:
            return{
                ...state,
                componentStates: state.componentStates.map((componentState) => {
                    if(componentState.id === action.componentKey){
                        if (componentState.selectedItem.length && !componentState.inputValue.length && action.keycode === 'backspace') {
                            return{
                                ...componentState,
                                selectedItem:componentState.selectedItem.slice(0, componentState.selectedItem.length - 1),
                            }
                        }
                        return componentState;
                    }
                    return componentState;
                })
            };

        case HANDLE_CHANGE_INPUT:
            return{
                ...state,
                componentStates: state.componentStates.map((componentState) => {
                    if(componentState.id === action.componentKey){
                        return {
                            ...componentState,
                            inputValue:action.newInput
                        }
                    }
                    return componentState;
                })
            };

        case HANDLE_CHANGE:
            return{
                ...state,
                componentStates: state.componentStates.map((componentState) => {
                    if(componentState.id === action.componentKey && !componentState.selectedItem.includes(action.item)){
                        return {
                            ...componentState,
                            inputValue:'',
                            selectedItem : componentState.selectedItem.concat(action.item)
                        }
                    }
                    return componentState;
                })
            };

        case HANDLE_DELETE:
            console.log(action);
            return{
                ...state,
                componentStates: state.componentStates.map((componentState) => {
                    if(componentState.id === action.componentKey){
                        return{
                            ...componentState,
                            selectedItem:componentState.selectedItem.filter((item) => item !== action.attribute)
                        };
                    }
                    return componentState;
                })
            };

        case ADD_TO_REDUCER:
            return{
                ...state,
                componentStates: state.componentStates.concat({
                    id:action.componentKey,
                    inputValue:'',
                    selectedItem:action.selection
                })
            };

        default:
            return state;
    }
}