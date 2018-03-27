import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

import Table, { TableBody,
    TableCell,
    TableHead,
    TableRow,TableFooter }      from 'material-ui/Table';
import { withStyles }           from 'material-ui/styles';
import Typography               from 'material-ui/Typography';
import Paper                    from 'material-ui/Paper';
import Button                   from 'material-ui/Button';
import TextField                from 'material-ui/TextField';
import { CircularProgress }     from 'material-ui/Progress';
import Dialog, {
    DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle, }              from 'material-ui/Dialog';

import {updateQuantity,setupInitial,deletePendingItem,
    handleCloseModalDelete,handleShowModalDelete} from '../actions/actionsPanier'; //Import your actions
import * as FontAwesome from 'react-icons/lib/fa';

const styles = theme => ({
    root: {
        display: 'flex',
        flexDirection:'column',
        flexWrap: 'wrap',
        justifyContent: 'center',
        overflow: 'hidden',
    },
    cellName:{
        borderBottom:'none',
        alignItems:'end',

    },
    cellQuantity:{
        borderBottom:'none',
        alignItems:'end',
    },
    cellDelete:{
        borderBottom:'none',
        alignItems:'end',
    },
    cellPrice:{
        borderBottom:'none',
        alignItems:'end',
    },
    progress: {
        margin: theme.spacing.unit * 2,
    },
    paper: {
        position: 'absolute',
        width: theme.spacing.unit * 50,
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
    },
});

class Screen extends React.Component {

    constructor(props){
        super(props);
    }

    render(){
        const { classes } = this.props;
        return (
            <Paper className={classes.root}>
                <Table className={classes.table}>
                    <TableHead>
                        <TableRow>
                            <TableCell>Commerce</TableCell>
                            <TableCell>Produit</TableCell>
                            <TableCell>Total</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {this.props.orders.map(commande => {
                            return (
                                <TableRow key={commande.commerce.nom}>
                                    <TableCell>{commande.commerce.nom}</TableCell>
                                    <TableCell>
                                        {commande.produits.map(produit => (
                                            <Table key={produit.id}>
                                                <TableHead style={{borderBottom:'none'}}>
                                                    <TableRow style={{height:0}}>
                                                        <TableCell numeric style={{flex:1,borderBottom:'none',width:150,flexWrap:'wrap'}}/>
                                                        <TableCell numeric style={{flex:1,borderBottom:'none',width:200,flexWrap:'wrap'}}/>
                                                        <TableCell numeric style={{flex:1,borderBottom:'none',width:7,paddingRight:0,flexWrap:'wrap'}}/>
                                                        <TableCell numeric style={{flex:1,borderBottom:'none',width:7,flexWrap:'wrap'}}/>
                                                    </TableRow>
                                                </TableHead>
                                                <TableBody>
                                                    <TableRow>
                                                        <TableCell className={classes.cellName}>
                                                            {produit.nom}
                                                        </TableCell>
                                                        <TableCell className={classes.cellQuantity}>
                                                            <TextField
                                                                id="quantite"
                                                                label="Quantité"
                                                                value={produit.quantite}
                                                                type="number"
                                                                onChange={this.props.updateQuantity.bind(null,produit)}/>
                                                        </TableCell>
                                                        <TableCell className={classes.cellPrice}>
                                                            {produit.prix.toFixed(2) + "€"}
                                                        </TableCell>
                                                        <TableCell className={classes.cellDelete}>
                                                            <Button color="primary" className={classes.button} onClick={() => this.props.handleShowModalDelete(produit)}>
                                                                <FontAwesome.FaTrash/>
                                                            </Button>
                                                        </TableCell>
                                                    </TableRow>
                                                </TableBody>
                                            </Table>
                                        ))}
                                    </TableCell>
                                    {this.showProgressOrTotal(commande)}
                                </TableRow>
                            );
                        })}
                    </TableBody>
                </Table>

                <Dialog
                    aria-labelledby="alert-dialog-title"
                    aria-describedby="alert-dialog-description"
                    open={this.props.showModalDelete}
                    onClose={this.props.handleCloseModalDelete}>

                    <DialogTitle id="alert-dialog-title">{"Supprimer l'article ?"}</DialogTitle>
                    <DialogContent>
                        <DialogContentText id="alert-dialog-description">
                            Cette action est irréversible
                        </DialogContentText>
                    </DialogContent>
                    <DialogActions>
                        <Button onClick={this.props.handleCloseModalDelete} color="primary" autoFocus>
                            Annuler
                        </Button>
                        <Button onClick={this.props.deletePendingItem} color="primary">
                            Supprimer
                        </Button>
                    </DialogActions>
                </Dialog>

            </Paper>
        );
    }

    showProgressOrTotal(order){
        if(!order.showCircularProgress){
            return(
                <TableCell>{order.cout.totalTvac.toFixed(2) + "€"}</TableCell>
            );
        }
        else {
            return(
                <TableCell>
                    <CircularProgress/>
                </TableCell>
            );

        }
    }

    componentDidMount(){
        this.props.setupInitial(this.props.panier);
    }
}

Screen.propTypes = {
    classes: PropTypes.object.isRequired,
};

function getModalStyle() {
    const top = 50;
    const left = 50;

    return {
        top: `${top}%`,
        left: `${left}%`,
        transform: `translate(-${top}%, -${left}%)`,
    };
}

const mapStateToProps = (state, own) => {
    return {
        ...own,
        loading: state.loading,
        orders: state.orders,
        showModalDelete: state.showModalDelete
    }
};

function mapDispatchToProps(dispatch,own) {
    return {
        ...own,
        updateQuantity: (evt,newValue) => dispatch(updateQuantity(newValue,evt)),
        setupInitial: (initial) => dispatch(setupInitial(initial)),
        deletePendingItem:() => dispatch(deletePendingItem()),
        handleCloseModalDelete:()=>dispatch(handleCloseModalDelete()),
        handleShowModalDelete:(product)=>dispatch(handleShowModalDelete(product))
    }
}

//Connect everything
const PanierDetails = connect(mapStateToProps, mapDispatchToProps)(Screen);
const PanierDetailsStyled = withStyles(styles)(PanierDetails);
export default PanierDetailsStyled;