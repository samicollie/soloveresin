export class Validator {
    static firstname = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre ni caractères spéciaux.";
    static lastname = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre ni caractères spéciaux.";
    static email = "L'adresse email n'est pas valide.";
    static password = "Le mot de passe doit contenir au moins 8 caractères avec une minuscule, une majuscule, un chiffre et un caractère spécial.";
    static confirmPassword = "Les deux mots de passe doivent être identiques.";
    static phoneNumber = "Le numéro de téléphone n'est pas valide.";
    static streetNumber = "Le numéro de rue est invalide.";
    static streetName = "Le nom de la rue est invalide.";
    static zipcode = "Le code postal est invalide.";
    static city = "La ville est invalide.";
    static productName ="Le nom de l'article est invalide.";
    static price = "Le prix n'est pas valide.";
    static categoryName = "Le nom de la catégorie est invalide";

    //validate the firstname
    static validateFirstname(firstname)
    {
        const regex = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u;
        if(regex.test(firstname) && firstname.length>= 3 && firstname.length <=25){
            return true;
        }else{
            return false;
        }
    }
    
    //validate the lastname
    static validateLastname(lastname)
    {
        const regex = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u;
        if(regex.test(lastname) && lastname.length>= 2 && lastname.length <=20){
            return true;
        }else{
            return false;
        }
    }
    
    //validate the email
    static validateEmail(email)
    {
        const regex = /^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/;
        return regex.test(email);
    }
    
    //validate the password 
    static validatePassword(password)
    {
        const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return regex.test(password);
    }

    //validate confirme password
    static validateConfirmationPassword(confirmPassword){
        const password = document.querySelector('input[name="password"]');
        return confirmPassword == password.value;
    }
    
    //validate the street number
    static validateStreetNumber(streetNumber)
    {
        const regex = /^\d{1,3}(?:(?:bis|ter)?)$/i;
        return regex.test(streetNumber);
    }
    
    //validate the street name
    static validateStreetName(streetName)
    {
        const regex = /^[a-zA-Z\s]+$/;
        return regex.test(streetName);
    }
    
    //validate the zipcode
    static validateZipcode(zipcode)
    {
        const regex = /^\d{5}$/;
        return regex.test(zipcode);
    }
    
    //validate the phone number
    static validatePhoneNumber(phoneNumber)
    {
        const regex = /^(?:\+33|0)(?:[1-9]\d{2}){4}|[67]\d{8}|9\d{9}|8\d{9}|5\d{9}$/;
        return regex.test(phoneNumber);
    }
    
    //validate the city
    static validateCity(city)
    {
        const regex = /^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u;
        return regex.test(city);
    }
    
    //validate the product name
    static validateProductName(name){
        const regex = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s\-\']+$/;
        return regex.test(name);
    }
    
    //validate the price
    static validatePrice(price)
    {
        const regex = /^[0-9]+(\.[0-9]{1,2})?$/;
        return regex.test(price);
    }
    
    //validate the category name
    static validateCategoryName(categoryName)
    {
        const regex = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s\-]+$/;
        return regex.test(categoryName);
    }

    //Remove the error message
    static unDisplayError(idName){
        document.querySelector(idName).innerText = "";
        document.querySelector(idName).style.display = 'none';
    }
    
    //display the error message
    static displayError(idName, errorMessage){
        document.querySelector(idName).innerText = errorMessage;
        document.querySelector(idName).style.display = 'block';
    }
}
