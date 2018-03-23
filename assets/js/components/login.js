import React from 'react';
import PropTypes from 'prop-types';

import Input, { InputLabel } from 'material-ui/Input';
import { withStyles } from 'material-ui/styles';
import Card, { CardActions, CardContent } from 'material-ui/Card';
import Button from 'material-ui/Button';
import Typography from 'material-ui/Typography';
import TextField from 'material-ui/TextField';
import { FormGroup, FormControlLabel, FormControl,FormHelperText } from 'material-ui/Form';
import Checkbox from 'material-ui/Checkbox';

const styles = theme => ({
    card: {
        display:'flex',
        flexDirection:'column',
        alignItems:'center',
        margin:15
    },
    button: {

    },
    buttonRaised: {
        margin:15
    },
    textField: {
        margin:15
    },
    checkBox: {

    }
});

class LoginForm extends React.Component {

    constructor(props){
        super(props);

        var hasCredentialsError = !(this.props.error == '' || this.props.error == null);

        this.state={
            username:'',
            password:'',
            errorUsername:this.props.error,
            errorPassword:'',
            fieldUsernameHasError:hasCredentialsError,
            fieldPasswordHasError:false
        }
    }

    handleChange = name => event => {
        this.setState({
            [name]: event.target.value,
        });
    };

    handleSubmit = event => {

        var hasError = false;
        if(this.state.username === ''){
            event.preventDefault();
            this.setState({
                errorUsername:"Veuillez remplir ce champ",
                fieldUsernameHasError:true
            });
            hasError = true;
        }

        if(this.state.password === ''){
            event.preventDefault();
            this.setState({
                errorPassword:"Veuillez remplir ce champ",
                fieldPasswordHasError:true
            });
            hasError = true;
        }

        if(hasError)event.preventDefault();

        return !hasError;
    };

    render(){
        const { classes } = this.props;
        return (
            <div >
                <Card>
                    <CardContent>
                        <form method="post" className={classes.card} onSubmit={this.handleSubmit}>


                            <TextField
                                color="secondary"
                                name="_username"
                                id="_username"
                                label="Nom d'utilisateur"
                                className={classes.textField}
                                helperText={this.state.errorUsername}
                                error={this.state.fieldUsernameHasError}
                                value={this.state.username}
                                onChange={this.handleChange('username')}
                                margin="normal"/>

                            <TextField
                                color="secondary"
                                name="_password"
                                id="_password"
                                label="Mot de passe"
                                type="password"
                                className={classes.textField}
                                helperText={this.state.errorPassword}
                                error={this.state.fieldPasswordHasError}
                                value={this.state.password}
                                onChange={this.handleChange('password')}
                                margin="normal"/>

                            <Button variant="raised" color="secondary" className={classes.buttonRaised} type="submit">
                                Connexion
                            </Button>
                            <Button className={classes.button} color="secondary">Mot de passe oublié ?</Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        );
    }
}

LoginForm.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(LoginForm);