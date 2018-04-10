export const stringLocalizer = (code) => {
    switch(code){
        case 'fr':
            return fr;

        default:
            return fr;
    }
};

export const fr = {
    //General
    cancel:"Annuler",
    delete:"Supprimer",
    validate:"Valider",

    //Login
    username:"Nom d'utilisateur",
    password:"Mot de passe",
    connection: "Connexion",
    forgotPassword: "Mot de passe oublié ?",
    mandatoryField: "Veuillez remplir ce champ",
    wrongCredentials: "Le nom d'utilisateur ou le mot de passe est incorrect",

    //Basket
    quantity: "Quantité",
    commerceColumn:"Commerce",
    productColumn: "Produit",
    totalColumn:"Total",
    stripeFee: "Frais de transport et Stripe",
    grandTotal: "Total à payer",

    deleteItemModalTitle: "Supprimer l'article ?",
    deleteItemModalDescription: "Cette action est irréversible",

    commentModalTitle:"Commentaire",
    commentModalPlaceholder:"Commentaire",
};