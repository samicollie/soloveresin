<?php 

namespace APP\Controllers\Traits;

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
        $pattern = '/^\d{1,3}(?:(?:bis|ter)?)$/i';
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
        $pattern = '/^[a-zA-Z\s\-]+$/u';

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

}