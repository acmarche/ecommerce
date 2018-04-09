import React from 'react';
import Downshift from 'downshift';
import { withStyles } from 'material-ui/styles';
import PropTypes from 'prop-types';
import TextField from 'material-ui/TextField';
import Paper from 'material-ui/Paper';
import { MenuItem } from 'material-ui/Menu';
import Chip from 'material-ui/Chip';
import {connect} from "react-redux";
import {handleInputChange,handleKeyDown,handleChange,handleDelete,addToReducer} from "../actions/actionsDownshift";

function renderInput(inputProps) {
    const { InputProps, classes, ref, ...other } = inputProps;

    return (
        <TextField
            InputProps={{
                inputRef: ref,
                classes: {
                    root: classes.inputRoot,
                },
                ...InputProps,
            }}
            {...other}
        />
    );
}

const styles = theme => ({
    root: {
        marginTop:20,
        flexBasis:'auto',
        flexGrow:1,
        alignItems:'center',
        justifyContent:'center'
    },
    container: {
        flexGrow: 1,
        position: 'relative',
    },
    paper: {
        position: 'absolute',
        zIndex: 1,
        marginTop: theme.spacing.unit,
        left: 0,
        right: 0,
    },
    chip: {
        margin: `${theme.spacing.unit / 2}px ${theme.spacing.unit / 4}px`,
        fontSize:10
    },
    inputRoot: {
        flexWrap: 'wrap',
    },
});

function renderSuggestion({ suggestion, index, itemProps, highlightedIndex, selectedItem }) {

    const isHighlighted = highlightedIndex === index;
    return (
        <MenuItem
            {...itemProps}
            key={suggestion.id}
            selected={isHighlighted}
            component="div"
            style={{
                fontSize:10,
                backgroundColor:"#f0f0f0",
                height:15
            }}>
            {suggestion.nom}
        </MenuItem>
    );
}
renderSuggestion.propTypes = {
    highlightedIndex: PropTypes.number,
    index: PropTypes.number,
    itemProps: PropTypes.object,
    selectedItem: PropTypes.string,
    suggestion: PropTypes.shape({ label: PropTypes.string }).isRequired,
};

function getSuggestions(suggestions, inputValue) {
    let count = 0;

    return suggestions.filter(suggestion => {
        const keep =
            (!inputValue || suggestion.nom.toLowerCase().indexOf(inputValue.toLowerCase()) !== -1) &&
            count < 5;

        if (keep) {
            count += 1;
        }

        return keep;
    });
}

class Screen extends React.Component {

    render() {
        const classes = this.props.classes;
        const key = this.props.identifier; //identify the calling component in the reducer
        const commandeProduit = this.props.commandeProduit;

        return (
            <Downshift inputValue={this.props.inputValue} onChange={(item) => {this.props.handleChange(key,this.attributeFromLabel(item),commandeProduit)}} selectedItem={(evt) => {this.props.selectedItem(evt)}}>
                {({
                      getInputProps,
                      getItemProps,
                      isOpen,
                      inputValue: inputValue2,
                      selectedItem: selectedItem2,
                      highlightedIndex,
                  }) => (
                    <div className={classes.container}>
                        {renderInput({
                            fullWidth: true,
                            classes,
                            InputProps: getInputProps({
                                startAdornment: this.props.selectedItem.map(item => {
                                    return (
                                        <Chip
                                            key={item.nom}
                                            tabIndex={-1}
                                            label={item.nom}
                                            className={classes.chip}
                                            onDelete={() => {this.props.handleDelete(key,item,this.props.commandeProduit)}}
                                        />
                                    )
                                }),
                                onChange: (evt) => {this.props.handleInputChange(key,evt)},
                                onKeyDown: (evt) => {this.props.handleKeyDown(key,evt)},
                                placeholder: this.props.label,
                                id: 'integration-downshift-multiple',
                            }),
                        })}
                        {isOpen ? (
                            <Paper className={classes.paper} square>
                                {getSuggestions(this.props.suggestions, inputValue2).map((suggestion, index) =>
                                    renderSuggestion({
                                        suggestion,
                                        index,
                                        itemProps: getItemProps({ item: suggestion.nom }),
                                        highlightedIndex,
                                        selectedItem: selectedItem2,
                                    }),
                                )}
                            </Paper>
                        ) : null}
                    </div>
                )}
            </Downshift>
        );
    }

    attributeFromLabel(attributeLabel){
        return this.props.suggestions
            .filter((itemAttribute) => itemAttribute.nom === attributeLabel)[0];
    }

    componentWillMount(){
        //Add this component (and it's selection) to the list of components handled by the downShiftReducer
        this.props.addToReducer(this.props.identifier,this.props.selection)
    }
}

function IntegrationDownshift(props) {
    const properties={
        ...props,
        classes:props.classes,
        suggestions:props.suggestions,
        selection:props.selection
    };
    return (
        <div className={properties.classes.root}>
            <Screen
                {...properties} />
        </div>
    );
}

const mapStateToProps = (state, own) => {
    //If the reducer has no component OR the new one is not contained in the current list
    if(state.downshiftReducer.componentStates.length === 0 ||
        !state.downshiftReducer.componentStates.some((item) => item.id === own.identifier)){
        return {
            ...own,
            inputValue:'',
            selectedItem:[],
        }
    }
    return {
        ...own,
        inputValue:state.downshiftReducer.componentStates.filter((item) => item.id === own.identifier)[0].inputValue,
        selectedItem:state.downshiftReducer.componentStates.filter((item) => item.id === own.identifier)[0].selectedItem
    }
};

function mapDispatchToProps(dispatch,own) {
    return {
        ...own,
        handleKeyDown:(key,evt) => dispatch(handleKeyDown(key,evt)),
        handleInputChange:(key,evt) => dispatch(handleInputChange(key,evt)),
        handleChange:(key,evt,order) => dispatch(handleChange(key,evt,order)),
        handleDelete:(key,item,order) => dispatch(handleDelete(key,item,order)),
        addToReducer:(key,selection) => dispatch(addToReducer(key,selection)),
    }
}

//Connect everything
export default withStyles(styles)(connect(mapStateToProps, mapDispatchToProps)(IntegrationDownshift));
