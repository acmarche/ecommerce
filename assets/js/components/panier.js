import React from 'react';
import PropTypes from 'prop-types';
import DownShift from './downShiftAttributes'
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
    handleCloseModalDelete,handleShowModalDelete,handleShowModalComment,deleteAttribut,
    handleCloseModalComment,postComment,commentChanged} from '../actions/actionsPanier'; //Import your actions
import * as FontAwesome         from 'react-icons/lib/fa';
import IconButton               from "material-ui/es/IconButton/IconButton";
import ListItemSecondaryAction  from "material-ui/es/List/ListItemSecondaryAction";
import ModalDelete from './modalDelete'
import ModalComment from './modalComment'
import {stringLocalizer} from '../strings'

const strings = stringLocalizer('fr');

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
    cellComment:{
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
                            <TableCell>{strings.commerceColumn}</TableCell>
                            <TableCell>{strings.productColumn}</TableCell>
                            <TableCell>{strings.totalColumn}</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {this.props.orders.map(commande => {
                            return (
                                <TableRow key={commande.commerce.nom}>
                                    <TableCell>{commande.commerce.nom}</TableCell>
                                    <TableCell>
                                        {commande.commandeProduits.map(commandeProduit => (
                                            <List className={classes.list} key={commandeProduit.id}>
                                                <ListItem className={classes.listProduit}>
                                                    <div>
                                                        <ListItemText secondary={commandeProduit.produit.nom} className={classes.cellName}/>
                                                        <Collapse in={commandeProduit.expanded} timeout="auto" unmountOnExit>
                                                            {
                                                                commandeProduit.produit.produitListingAttributs.map(listingAttribut => {
                                                                    return (
                                                                        <DownShift
                                                                            key={listingAttribut.id}
                                                                            identifier={listingAttribut.id}
                                                                            commandeProduit={commandeProduit}
                                                                            label={listingAttribut.listingAttributs.nom}
                                                                            suggestions={listingAttribut.listingAttributs.attributs}
                                                                            //Seulement les attributs qui font partie du listing à afficher
                                                                            selection={commandeProduit.attributs.filter((attr) => attr.listingAttributId === listingAttribut.listingAttributs.id)}/>
                                                                    )
                                                                })}
                                                            <div style={{height:180}}>

                                                            </div>
                                                        </Collapse>
                                                    </div>
                                                    <ListItem button onClick={() => {this.props.toggleExpandItemAttributes(commandeProduit)}}>
                                                        {commandeProduit.produit.produitListingAttributs.length > 0 ?
                                                            commandeProduit.expanded ?
                                                                <ExpandLess /> : <ExpandMore />
                                                            :<Remove/>
                                                        }
                                                    </ListItem>

                                                </ListItem>

                                                <ListItem>
                                                    <TextField
                                                        id="quantite"
                                                        label={strings.quantity}
                                                        value={commandeProduit.quantite}
                                                        type="number"
                                                        onChange={(evt) => {this.props.updateQuantity(evt,commandeProduit)}}/>
                                                </ListItem>

                                                <ListItem>
                                                    {
                                                        commandeProduit.showCircularProgress ?
                                                            <CircularProgress/>:
                                                            <ListItemText primary={commandeProduit.prix.toFixed(2) + "€"}/>
                                                    }
                                                </ListItem>

                                                <ListItem button onClick={() => this.props.handleShowModalDelete(commandeProduit)} color="primary" className={classes.cellDelete}>
                                                    <FontAwesome.FaTrash/>
                                                </ListItem>

                                                {
                                                    commandeProduit.showCircularProgressComment ?

                                                        <ListItem color="primary" className={classes.cellComment}>
                                                            <CircularProgress/>
                                                        </ListItem> :

                                                        <ListItem button onClick={() => this.props.handleShowModalComment(commandeProduit)} color="primary" className={classes.cellComment}>
                                                            <FontAwesome.FaCommentO/>
                                                        </ListItem>
                                                }
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
                            <TableCell className={classes.cellStripeTotal}>{strings.stripeFee}</TableCell>
                            <TableCell className={classes.cellStripeTotal}/>
                            <TableCell className={classes.cellStripeTotal}>
                                {this.props.loading ? <CircularProgress/> : this.props.totalWithStripe.toFixed(2) + "€" }
                            </TableCell>
                        </TableRow>
                        <TableRow>
                            <TableCell className={classes.cellGrandTotal}>{strings.grandTotal}</TableCell>
                            <TableCell className={classes.cellGrandTotal}/>
                            <TableCell className={classes.cellGrandTotal}>
                                {this.props.loading ? <CircularProgress/> : this.props.grandTotal.toFixed(2) + "€" }
                            </TableCell>
                        </TableRow>


                    </TableBody>
                </Table>

                <ModalDelete
                    showModalDelete={this.props.showModalDelete}
                    handleCloseModalDelete={this.props.handleCloseModalDelete}
                    deletePendingItem={this.props.deletePendingItem}/>

                <ModalComment
                    handleCloseModalComment={this.props.handleCloseModalComment}
                    showModalComment={this.props.showModalComment}
                    comment={this.props.comment}
                    commentValueChanged={this.props.commentChanged}
                    postComment={this.props.postComment}/>

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
        loading: state.panierReducer.loading,
        orders: state.panierReducer.orders,
        showModalDelete: state.panierReducer.showModalDelete,
        showModalComment: state.panierReducer.showModalComment,
        totalWithStripe:state.panierReducer.totalWithStripe,
        grandTotal:state.panierReducer.grandTotal,
        comment:state.panierReducer.comment,
    }
};

function mapDispatchToProps(dispatch,own) {
    return {
        ...own,
        updateQuantity: (evt,newValue) => dispatch(updateQuantity(evt,newValue)),
        setupInitial: (initial) => dispatch(setupInitial(initial)),
        deleteAttribut:(product,attr) => dispatch(deleteAttribut(product,attr)),

        handleCloseModalDelete:()=>dispatch(handleCloseModalDelete()),
        handleShowModalDelete:(product)=>dispatch(handleShowModalDelete(product)),
        deletePendingItem:() => dispatch(deletePendingItem()),

        handleShowModalComment:(product)=>dispatch(handleShowModalComment(product)),
        handleCloseModalComment:(product)=>dispatch(handleCloseModalComment(product)),
        commentChanged:(evt)=>dispatch(commentChanged(evt)),
        postComment:()=>dispatch(postComment()),

        toggleExpandItemAttributes:(product)=>dispatch(toggleExpandItemAttributes(product)),
    }
}

//Connect everything
const PanierDetails = connect(mapStateToProps, mapDispatchToProps)(Screen);
const PanierDetailsStyled = withStyles(styles)(PanierDetails);
export default PanierDetailsStyled;