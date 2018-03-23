import React from 'react';
import PropTypes from 'prop-types';
import { withStyles } from 'material-ui/styles';
import { connect } from 'react-redux';
import Table, { TableBody, TableCell, TableHead, TableRow,TableFooter } from 'material-ui/Table';
import Paper from 'material-ui/Paper';
import GridList, { GridListTile, GridListTileBar } from 'material-ui/GridList';
import Button from 'material-ui/Button';
import TextField from 'material-ui/TextField';
import {updateQuantity,setupInitial,deleteItem} from '../actions/actionsPanier'; //Import your actions
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
                        {this.props.products.map(commande => {
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
                                                            {produit.prix}
                                                        </TableCell>
                                                        <TableCell className={classes.cellDelete}>
                                                            <Button color="primary" className={classes.button} onClick={this.props.deleteItem.bind(null,produit)}>
                                                                <FontAwesome.FaTrash/>
                                                            </Button>
                                                        </TableCell>
                                                    </TableRow>
                                                </TableBody>
                                            </Table>
                                        ))}
                                    </TableCell>
                                    <TableCell>{commande.total}</TableCell>
                                </TableRow>
                            );
                        })}
                    </TableBody>
                </Table>
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

const mapStateToProps = (state, own) => {
    return {
        ...own,
        loading: state.loading,
        products: state.products
    }
};

function mapDispatchToProps(dispatch,own) {
    return {
        ...own,
        updateQuantity: (evt,newValue) => dispatch(updateQuantity(newValue,evt)),
        setupInitial: (initial) => dispatch(setupInitial(initial)),
        deleteItem:(item,evt) => dispatch(deleteItem(item))
    }
}

//Connect everything
const PanierDetails = connect(mapStateToProps, mapDispatchToProps)(Screen);
const PanierDetailsStyled = withStyles(styles)(PanierDetails);
export default PanierDetailsStyled;