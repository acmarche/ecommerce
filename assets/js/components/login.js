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
import { MuiThemeProvider, createMuiTheme } from 'material-ui/styles';
import {stringLocalizer} from '../strings'

const strings = stringLocalizer('fr');

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


const theme = createMuiTheme({
    palette: {
        primary: {
            light: '#e3f2fd',
            main: '#2196f3',
            dark: '#1976d2',
            contrastText: '#fff',
        },
        secondary: {
            light: '#fbbcd0',
            main: '#f50057',
            dark: '#c51162',
            contrastText: '#fff',
        },
    },
});


class LoginForm extends React.Component {

    constructor(props){
        super(props);

        let hasCredentialsError = !(this.props.error === '' || this.props.error == null);

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

        let hasError = false;
        if(this.state.username === ''){
            event.preventDefault();
            this.setState({
                errorUsername: strings.mandatoryField,
                fieldUsernameHasError:true
            });
            hasError = true;
        }

        if(this.state.password === ''){
            event.preventDefault();
            this.setState({
                errorPassword: strings.mandatoryField,
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
            <MuiThemeProvider theme={theme}>
                <div >
                <Card>
                    <CardContent>
                        <form method="post" className={classes.card} onSubmit={this.handleSubmit}>
                            <TextField
                                color="secondary"
                                name="_username"
                                id="_username"
                                label={strings.username}
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
                                label={strings.password}
                                type="password"
                                className={classes.textField}
                                helperText={this.state.errorPassword}
                                error={this.state.fieldPasswordHasError}
                                value={this.state.password}
                                onChange={this.handleChange('password')}
                                margin="normal"/>

                            <Button variant="raised" color='primary' className={classes.buttonRaised} type="submit">
                                {strings.connection}
                            </Button>
                            <Button className={classes.button} color="primary">{strings.forgotPassword}</Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
            </MuiThemeProvider>
        );
    }
}

LoginForm.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(styles)(LoginForm);