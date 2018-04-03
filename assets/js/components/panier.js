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
import List,{ ListItem,
    ListItemIcon,
    ListItemText }              from 'material-ui/List';
import Divider                  from 'material-ui/Divider';
import TrashIcon                from 'material-ui-icons/Delete';
import DraftsIcon               from 'material-ui-icons/Drafts';
import Collapse                 from 'material-ui/transitions/Collapse';
import ExpandLess               from 'material-ui-icons/ExpandLess';
import Remove                   from 'material-ui-icons/Remove';
import ExpandMore               from 'material-ui-icons/ExpandMore';
import Button                   from 'material-ui/Button';
import TextField                from 'material-ui/TextField';
import { CircularProgress }     from 'material-ui/Progress';
import Dialog, {
    DialogActions,
    DialogContent,
    DialogContentText,
    DialogTitle, }              from 'material-ui/Dialog';
import {updateQuantity,setupInitial,deletePendingItem,toggleExpandItemAttributes,
    handleCloseModalDelete,handleShowModalDelete,deleteAttribut} from '../actions/actionsPanier'; //Import your actions
import * as FontAwesome         from 'react-icons/lib/fa';
import IconButton               from "material-ui/es/IconButton/IconButton";
import ListItemSecondaryAction  from "material-ui/es/List/ListItemSecondaryAction";

const styles = theme => ({
    root: {
        display: 'flex',
        flexDirection:'column',
        flexWrap: 'wrap',
        justifyContent: 'center',
        overflow: 'hidden',
    },
    cellName:{
        width:180
    },
    cellDelete:{
        width:60,
        height:60
    },
    cellTotalOrder:{
        width:200
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
    list :{
        display: 'flex',
        flexDirection: 'row',
        alignItems:'center',
        padding: 0,
    },
    listProduit :{
        display: 'flex',
        flexDirection: 'row',
        alignItems:'center',
        padding: 0,
    },
    cellStripeTotal:{
        fontSize:'12pt'
    },
    cellGrandTotal:{
        fontSize:'19pt'
    },
    attribut:{
        display: 'flex',
        alignItems:'end'
    },
    attributName:{
        width:180
    },
    iconAttributDelete:{
        width:15
    }
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
                                            <List className={classes.list} key={produit.id}>
                                                <ListItem className={classes.listProduit}>
                                                    <div>
                                                        <ListItemText secondary={produit.nom} className={classes.cellName}/>
                                                        <Collapse in={produit.expanded} timeout="auto" unmountOnExit>
                                                            <List dense={true}>
                                                                {produit.attributs.map((attribut) =>{
                                                                    return(
                                                                        <ListItem key={attribut.id}>
                                                                            <ListItemText secondary={attribut.nom} className={classes.attributName}/>
                                                                            <ListItemSecondaryAction onClick={() => this.props.deleteAttribut(produit,attribut)}>
                                                                                <IconButton>
                                                                                    <FontAwesome.FaMinusCircle  className={classes.iconAttributDelete}/>
                                                                                </IconButton>
                                                                            </ListItemSecondaryAction>
                                                                        </ListItem>
                                                                    )
                                                                })}
                                                            </List>
                                                        </Collapse>
                                                    </div>
                                                    <ListItem button onClick={() => {this.props.toggleExpandItemAttributes(produit)}}>
                                                        {produit.attributs.length > 0 ?
                                                            produit.expanded ?
                                                                <ExpandLess /> : <ExpandMore />
                                                            :<Remove/>
                                                        }
                                                    </ListItem>

                                                </ListItem>

                                                <ListItem>
                                                    <TextField
                                                        id="quantite"
                                                        label="Quantité"
                                                        value={produit.quantite}
                                                        type="number"
                                                        onChange={this.props.updateQuantity.bind(null,produit)}/>
                                                </ListItem>

                                                <ListItem>
                                                    <ListItemText primary={produit.prix.toFixed(2) + "€"}/>
                                                </ListItem>

                                                <ListItem button onClick={() => this.props.handleShowModalDelete(produit)} color="primary" className={classes.cellDelete}>
                                                    <FontAwesome.FaTrash/>
                                                </ListItem>
                                            </List>
                                        ))}
                                    </TableCell>
                                    <TableCell className={classes.cellTotalOrder}>
                                        {commande.showCircularProgress ? <CircularProgress/> : commande.cout.totalTvac.toFixed(2) + "€"}
                                    </TableCell>
                                </TableRow>
                            );
                        })}
                        <TableRow>
                            <TableCell className={classes.cellStripeTotal}>Frais de transport et Stripe</TableCell>
                            <TableCell className={classes.cellStripeTotal}/>
                            <TableCell className={classes.cellStripeTotal}>
                                {this.props.loading ? <CircularProgress/> : this.props.totalWithStripe.toFixed(2) + "€" }
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell className={classes.cellGrandTotal}>Total à payer</TableCell>
                            <TableCell className={classes.cellGrandTotal}/>
                            <TableCell className={classes.cellGrandTotal}>
                                {this.props.loading ? <CircularProgress/> : this.props.grandTotal.toFixed(2) + "€" }
                            </TableCell>
                        </TableRow>

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
        showModalDelete: state.showModalDelete,
        totalWithStripe:state.totalWithStripe,
        grandTotal:state.grandTotal
    }
};

function mapDispatchToProps(dispatch,own) {
    return {
        ...own,
        updateQuantity: (evt,newValue) => dispatch(updateQuantity(newValue,evt)),
        setupInitial: (initial) => dispatch(setupInitial(initial)),
        deletePendingItem:() => dispatch(deletePendingItem()),
        deleteAttribut:(product,attr) => dispatch(deleteAttribut(product,attr)),
        handleCloseModalDelete:()=>dispatch(handleCloseModalDelete()),
        handleShowModalDelete:(product)=>dispatch(handleShowModalDelete(product)),
        toggleExpandItemAttributes:(product)=>dispatch(toggleExpandItemAttributes(product))
    }
}

//Connect everything
const PanierDetails = connect(mapStateToProps, mapDispatchToProps)(Screen);
const PanierDetailsStyled = withStyles(styles)(PanierDetails);
export default PanierDetailsStyled;