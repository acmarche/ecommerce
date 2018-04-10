import React from "react";
import Dialog from "material-ui/es/Dialog/Dialog";
import DialogTitle from "material-ui/es/Dialog/DialogTitle";
import DialogContent from "material-ui/es/Dialog/DialogContent";
import DialogActions from "material-ui/es/Dialog/DialogActions";
import Button from "material-ui/es/Button/Button";
import withStyles from "material-ui/es/styles/withStyles";
import connect from "react-redux/es/connect/connect";
import DialogContentText from "material-ui/es/Dialog/DialogContentText";
import PropTypes from "prop-types";
import {stringLocalizer} from '../strings'

const strings = stringLocalizer('fr');

export default class ModalDelete extends React.Component {

    constructor(props){
        super(props);
    }

    render(){
        const { classes } = this.props;
        return (
            <Dialog
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
                open={this.props.showModalDelete}
                onClose={this.props.handleCloseModalDelete}>

                <DialogTitle id="alert-dialog-title">{strings.deleteItemModalTitle}</DialogTitle>
                <DialogContent>
                    <DialogContentText id="alert-dialog-description">
                        {strings.deleteItemModalDescription}
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={this.props.handleCloseModalDelete} color="primary" autoFocus>
                        {strings.cancel}
                    </Button>
                    <Button onClick={this.props.deletePendingItem} color="primary">
                        {strings.delete}
                    </Button>
                </DialogActions>
            </Dialog>
        );
    }
}

ModalDelete.propTypes = {
    handleCloseModalDelete: PropTypes.func.isRequired,
    showModalDelete: PropTypes.bool.isRequired,
    deletePendingItem: PropTypes.func.isRequired
};
