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
import ModalDelete from "./modalDelete";
import TextField from "material-ui/TextField/TextField";
import {stringLocalizer} from '../strings'

const strings = stringLocalizer('fr');

const styles = theme => ({
    root: {
        width:500,
    },
});


class ModalComment extends React.Component {

    constructor(props){
        super(props);
    }

    render(){
        const { classes } = this.props;
        return (
            <Dialog
                aria-labelledby="alert-dialog-title"
                aria-describedby="alert-dialog-description"
                fullWidth={true}
                maxWidth = {'sm'}
                open={this.props.showModalComment}
                onClose={this.props.handleCloseModalComment}>

                <DialogTitle id="alert-dialog-title">{strings.commentModalTitle}</DialogTitle>
                <DialogContent>
                    <TextField
                        id="textarea"
                        label={strings.commentModalPlaceholder}
                        multiline
                        fullWidth
                        onChange={this.props.commentValueChanged}
                        value={this.props.comment}
                        margin="normal"
                    />

                </DialogContent>
                <DialogActions>
                    <Button onClick={this.props.handleCloseModalComment} color="primary" autoFocus>
                        {strings.cancel}
                    </Button>
                    <Button onClick={this.props.postComment} color="primary">
                        {strings.validate}
                    </Button>
                </DialogActions>
            </Dialog>
        );
    }
}

ModalComment.propTypes = {
    handleCloseModalComment: PropTypes.func.isRequired,
    showModalComment: PropTypes.bool.isRequired,
    
    comment: PropTypes.string.isRequired,
    commentValueChanged: PropTypes.func.isRequired,
    
    postComment: PropTypes.func.isRequired
};

export default withStyles(styles)(ModalComment);