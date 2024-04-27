<?php 

namespace APP\Controllers\Traits;

use DateTime;

trait Validatortrait {

    /**
     * validate if a string doesn't contain digit
     *
     * @param string $string
     * @return boolean
     */
    public function validateStringWithoutDigit(string $string): bool
    {
        return !preg_match('/\d/', $string);
    }

    /**
     * validate if a string contains only letters and digits
     *
     * @param string $string
     * @return boolean
     */
    public function validateStringWithOnlyLettersAndDigits(string $string): bool
    {
        return preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s\-\']+$/', $string);
    }

    /**
     * Validate the firstname in a formular
     *
     * @param string $firstname
     * @return boolean
     */
    public function validateFirstname(string $firstname): bool
    {
        $pattern = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u';
        if(preg_match($pattern, $firstname)){
            //test if the length of the string is between 2 and 25
            $options = [
                'options' => [
                    'min_range' => 3,
                    'max_range' => 25
                ]
            ];
            
            return filter_var(strlen($firstname), FILTER_VALIDATE_INT, $options);
        }else{
            return false;
        }
        
    }
    
    /**
     * Validate the lastname in a formular
     *
     * @param string $lastname
     * @return boolean
     */
    public function validateLastname(string $lastname): bool
    {
        $pattern = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u';
        if(preg_match($pattern, $lastname)){
            //test if the lengt of the string is higher than 2
            $options = [
                'options' => [
                    'min_range' => 2,
                    'max_range' => 20
                ]
            ];
            
            return filter_var(strlen($lastname), FILTER_VALIDATE_INT, $options);
        }else{
            return false;
        }
    }

    /**
     * validate the email in a formular
     *
     * @param string $email
     * @return boolean
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * validate that a password is safe
     *
     * @param string $password
     * @return boolean
     */
    public function validatePassword(string $password): bool
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        return preg_match($pattern, $password);
    }

    /**
     * validate if password and confirm password are the same
     *
     * @param string $password
     * @param string $confirmPassword
     * @return boolean
     */
    public function validateConfirmPassword(string $password, string $confirmPassword): bool
    {
        return $password === $confirmPassword ? true : false;
    }

    /**
     * validate the street number
     *
     * @param string $streetNumber
     * @return boolean
     */
    public function validateStreetNumber(string $streetNumber): bool
    {
        $pattern = '/^(\d{1,3}(?:(?:bis|ter)?))??$/i';
        return preg_match($pattern, $streetNumber);
    }

    /**
     * validate the street name
     *
     * @param string $streetName
     * @return boolean
     */
    public function validateStreetName(string $streetName): bool
    {
        $pattern = '/^[a-zA-Z\s]+$/';

        return preg_match($pattern, $streetName);
    }

    /**
     * validate the zipcode
     *
     * @param string $zipCode
     * @return boolean
     */
    public function validateZipCode(string $zipCode): bool
    {
        $pattern = '/^\d{5}$/';

        return preg_match($pattern, $zipCode);
    }

    /**
     * validate the name of the city
     *
     * @param string $city
     * @return boolean
     */
    public function validateCity(string $city): bool
    {
        $pattern = '/^[a-zA-ZÀ-ÖØ-öø-ÿ]+(?:-[a-zA-ZÀ-ÖØ-öø-ÿ]+)?$/u';

        return preg_match($pattern, $city);
    }

    /**
     * validate the phone number
     *
     * @param string $phoneNumber
     * @return boolean
     */
    public function validatePhoneNumber(string $phoneNumber): bool 
    {
        $pattern = '/^(?:\+33|0)(?:[1-9]\d{2}){4}|[67]\d{8}|9\d{9}|8\d{9}|5\d{9}$/';

        return preg_match($pattern, $phoneNumber);
    }

    /**
     * verify if the MIME Type is JPEG or JPG
     *
     * @param string $filePath
     * @return boolean
     */
    public function isJPEG(string $filePath): bool
    {
        $allowedTypes = ['image/jpeg', 'image/jpg'];
        $fileType = mime_content_type($filePath);

        return in_array($fileType, $allowedTypes);
    }

    /**
     * verify if the size of picture is less a limit size
     *
     * @param string $filePath
     * @return boolean
     */
    public function isImageSizeValid(string $filePath): bool
    {
        //get informations on the picture
        $imageInfo = getimagesize($filePath);

        if($imageInfo === false){
            // no informations is getting.
            return false;
        }
        //get the size of the picture
        $fileSize = filesize($filePath);

        //limit of the size at 500 Ko
        $maxFileSize = 500 * 1024;

        //verify if the memory size of the picture is less the limit
        return ($fileSize <= $maxFileSize);
    }

    /**
     * validate if a string is in a price format
     *
     * @param string $price
     * @return bool
     */
    public function validatePrice(string $price): bool
    {
        $pattern = '/^[0-9]+(\.[0-9]{1,2})?$/';
        return preg_match($pattern, $price);
    }

    /**
     * validate the expiration date of a paiement card
     *
     * @param string $date
     * @return boolean
     */
    public function validateExpirationDate(string $date): bool
    {
        $pattern = '/^(0[1-9]|1[0-2])\/([0-9]{2})$/';
        if (!preg_match($pattern,$date)) {
            return false;
        }
    
        //extract the month and year
        $parts = explode('/', $date);
        $month = intval($parts[0], 10);
        $year = intval($parts[1], 10);
        $currentYear = intval(date('y')); // get only to last digit
        
        if ($year < $currentYear || $year > $currentYear + 5) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }
        return true;
    }

    /**
     * validate the paiement card number
     *
     * @param string $cardNumber
     * @return boolean
     */
    public function validateCardNumber(string $cardNumber): bool
    {
        $pattern = '/\d{4}-\d{4}-\d{4}-\d{4}/';
        return preg_match($pattern, $cardNumber);
    }

    /**
     * validate the card visual value
     *
     * @param string $cvv
     * @return boolean
     */
    public function validateCVV(string $cvv): bool
    {
        $pattern = '/\d{3}/';
        return preg_match($pattern, $cvv);
    }

    /**
     * clean the data from formular fields
     *
     * @param array $fields
     * @return array
     */
    public function cleanFields(array $fields): array
    {
        $cleanedFields = [];
        foreach($fields as $fieldName => $fieldValue){
            //clean the value of the field
            $cleanedValue = htmlspecialchars(strip_tags($fieldValue));
            $cleanedFields[$fieldName] = $cleanedValue;
        }
        return $cleanedFields;
    }

    public function cleanUploadedImage(array $files): array
    {
        $file = [];
        if(isset($files['image']['tmp_name'])){
            $file['imageName'] = htmlspecialchars(strip_tags($files['image']['name']));
        }
        if(isset($files['image']['size'])){
            $file['sizeImage'] = htmlspecialchars(strip_tags($files['image']['size']));
        }
        return $file;
    }

    /**
     * validate data in formular fields
     *
     * @param array $fields
     * @return array
     */
    public function validateFields(array $fields): array
    {
        $errorMessage = [];

        if(isset($fields['firstname']) && !$this->validateFirstname($fields['firstname'])){
            $errorMessage['firstname'] = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre ni caractères spéciaux.";
        }
        if(isset($fields['lastname']) && !$this->validateLastname($fields['lastname'])){
            $errorMessage['lastname'] = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre ni caractères spéciaux.";
        }
        if(isset($fields['email']) && !$this->validateEmail($fields['email'])){
            $errorMessage['email'] = "L'adresse email n'est pas valide.";
        }
        if(isset($fields['password']) && !$this->validatePassword($fields['password'])){
            $errorMessage['password'] = "Le mot de passe doit contenir au moins 8 caractères avec une minuscule, une majuscule, un chiffre et un caractère spécial.";
        }
        if(isset($fields['password']) && isset($fields['confirmation-password']) && !$this->validateConfirmPassword($fields['password'], $fields['confirmation-password'])){
            $errorMessage['confirmationPassword'] = "Les deux mots de passe doivent être identiques.";
        }
        if(isset($fields['phone-number']) && !$this->validatePhoneNumber($fields['phone-number'])){
            $errorMessage['phoneNumber'] = "Le numéro de téléphone n'est pas valide.";
        }
        if(isset($fields['street-number']) && !$this->validateStreetNumber($fields['street-number'])){
            $errorMessage["streetNumber"] = "Le numéro de rue est invalide.";
        }
        if(isset($fields['street-name']) && !$this->validateStreetName($fields['street-name'])){
            $errorMessage["streetName"] = "Le nom de la rue est invalide.";
        }
        if(isset($fields['zipcode']) && !$this->validateZipCode($fields['zipcode'])){
            $errorMessage["zipcode"] = "Le code postal est invalide.";
        }
        if(isset($fields['city']) && !$this->validateCity($fields['city'])){
            $errorMessage["city"] = "La ville est invalide.";
        }
        if(isset($fields['name']) && !$this->validateStringWithOnlyLettersAndDigits($_POST['name'])){
            $errorMessage['productName'] = "Le nom de l'article est invalide.";
        }
        if(isset($fields['price']) && !$this->validatePrice(($_POST['price']))){
            $errorMessage['price'] = "Le prix n'est pas valide.";
        }
        if(isset($fields['image']['tmp_name']) && !empty($fields['image']['tmp_name']) && !$this->isJPEG($fields['image']['tmp_name'])){
            $errorMessage['imageType'] = "L'image doit être au format JPEG.";
        }
        if(isset($fileds['image']['tmp_name']) && !empty($fields['image']['tmp_name']) && !$this->isImageSizeValid($fileds['image']['tmp_name'])){
            $errorMessage['imageSize'] = "L'image est trop grande.";
        }
        if(isset($fields['category']) && !$this->validateStringWithOnlyLettersAndDigits($fields['category'])){
            $errorMessage['categoryName'] = "Le nom de catégorie est invalide.";
        }
        if(isset($fields['expiration-date']) && !$this->validateExpirationDate($fields['expiration-date'])){
            $errorMessage['expirationDate'] = "La date d'expiration est invalide.";
        }
        if(isset($fields['card-number']) && !$this->validateCardNumber($fields['card-number'])){
            $errorMessage['cardNumber'] = "Le numéro de carte est invalide.";
        }
        if(isset($fields['cvv']) && !$this->validateCVV($fields['cvv'])){
            $errorMessage['cvv'] = "Le cryptogramme visuel est invalide.";
        }


        return  $errorMessage;
    }

}