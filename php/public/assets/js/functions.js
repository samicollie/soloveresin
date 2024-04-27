import { Validator } from "./validator.js";


export function nextSlide(carouselImages, carouselSlide, slideWidth) {
    let currentIndex = globalThis.currentIndex;
    let direction = globalThis.direction;
    
    if (currentIndex >= carouselImages.length - 1) {
        direction = -1; // reverse the direction
    }
    if (currentIndex <= 0) {
        direction = 1; // come back to the normal direction
    }
    currentIndex += direction;
    carouselSlide.style.transition = 'transform 0.4s ease-in-out';
    carouselSlide.style.transform = `translateX(${-(slideWidth + 10) * currentIndex}px)`;

    // update the global variables
    globalThis.currentIndex = currentIndex;
    globalThis.direction = direction;
}

// function to display the rating depends on the rating of the product
export function displayRating(rating, element) {
    const stars = element.querySelectorAll('.star-inner');

    stars.forEach((star, index) => {
        if (rating >= index + 1) {
            star.classList.add('star-filled');
        } else {
            const decimal = rating - index;
            if(index + 1 - rating < 1){
                if(decimal > 0 && decimal < 0.5){
                    star.classList.add('star-quarter');
                }else if( decimal < 0.75 ){
                    star.classList.add('star-half');
                }else{
                    star.classList.add('star-three-quarters');
                }
            }
        }
    });
}

//function send AJAX request
export function sendAjaxRequest(url,method, formData, JSONResponse,  successCallback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);
    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (JSONResponse) {
                const response = JSON.parse(xhr.responseText);
                successCallback(response);
            } else {
                const response = xhr.responseText;
                successCallback(response);
            }
        } else {
            console.error(`Error: ${xhr.status}`);
        }
    };
    xhr.send(formData);
}

//function to manage the search bar event
export function handleSearch(searchInput, currentUrl){
    const formData = new FormData();
    formData.append("search", searchInput.value);
    const requestUrl = currentUrl.includes('/admin/') ? "/admin/products/list" : "/store";
    sendAjaxRequest(requestUrl,"POST", formData, false, (response)=>{
        document.querySelector('#content-to-replaced').innerHTML = response;
    });
}


//function displaying success messages
export function displaySuccess(success){
    //reset the success message
    resetSuccessMessage();

    //display the message
    document.querySelector('#success-message').innerText = success;
    document.querySelector('#success-message').style.display = 'block';
}

//reset the success message.
export function resetSuccessMessage(){
    const success = document.querySelector('#success-message');
    if(success){
        success.innerText = '';
        success.style.display = 'none';
    }
}

//function displaying error messages into the formular
export function displayErrors(errors){
    // reset the error messages
    resetErrorMessages();

    //display the messages 
    if(errors.hasOwnProperty('firstname')){
        document.querySelector('#error-firstname').innerText = errors.firstname;
        document.querySelector('#error-firstname').style.display = 'block';
    }
    if(errors.hasOwnProperty('lastname')){
        document.querySelector('#error-lastname').innerText = errors.lastname;
        document.querySelector('#error-lastname').style.display = 'block';
    }
    if(errors.hasOwnProperty('email')){
        document.querySelector('#error-email').innerText = errors.email;
        document.querySelector('#error-email').style.display = 'block';
    }
    if(errors.hasOwnProperty('password')){
        document.querySelector('#error-password').innerText = errors.password;
        document.querySelector('#error-password').style.display = 'block';
    }
    if(errors.hasOwnProperty('confirmationPassword')){
        document.querySelector('#error-confirmation-password').innerText = errors.confirmationPassword;
        document.querySelector('#error-confirmation-password').style.display = 'block';
    }
    if(errors.hasOwnProperty('blank')){
        document.querySelector('#error-blank').innerText = errors.blank;
        document.querySelector('#error-blank').style.display = 'block';
    }
    if(errors.hasOwnProperty('phone-number')){
        document.querySelector('#error-phone-number').innerText = errors.phoneNumber;
        document.querySelector('#error-phone-number').style.display = 'block';
    }
    if(errors.hasOwnProperty('streetNumber')){
        document.querySelector('#error-street-number').innerText = errors.streetNumber;
        document.querySelector('#error-street-number').style.display = 'block';
    }
    if(errors.hasOwnProperty('streetName')){
        document.querySelector('#error-street-name').innerText = errors.streetName;
        document.querySelector('#error-street-name').style.display = 'block';
    }
    if(errors.hasOwnProperty('zipcode')){
        document.querySelector('#error-zipcode').innerText = errors.zipcode;
        document.querySelector('#error-zipcode').style.display = 'block';
    }
    if(errors.hasOwnProperty('productName')){
        document.querySelector('#error-product-name').innerText = errors.productName;
        document.querySelector('#error-product-name').style.display = 'block';
    }
    if(errors.hasOwnProperty('price')){
        document.querySelector('#error-price').innerText = errors.price;
        document.querySelector('#error-price').style.display = 'block';
    }
    if(errors.hasOwnProperty('imageType')){
        document.querySelector('#error-image-type').innerText = errors.imageType;
        document.querySelector('#error-image-type').style.display = 'block';
    }
    if(errors.hasOwnProperty('imageSize')){
        document.querySelector('#error-image-size').innerText = errors.imageSize;
        document.querySelector('#error-image-size').style.display = 'block';
    }
    if(errors.hasOwnProperty('categoryName')){
        document.querySelector('#error-category').innerText = errors.categoryName;
        document.querySelector('#error-category').style.display = 'block';
    }
    if(errors.hasOwnProperty('expirationDate')){
        document.querySelector('#error-expiration-date').innerText = errors.expirationDate;
        document.querySelector('#error-expiration-date').style.display = 'block';
    }
    if(errors.hasOwnProperty('cardNumber')){
        document.querySelector('#error-card-number').innerText = errors.cardNumber;
        document.querySelector('#error-card-number').style.display = 'block';
    }
    if(errors.hasOwnProperty('cvv')){
        document.querySelector('#error-cvv').innerText = errors.cvv;
        document.querySelector('#error-cvv').style.display = 'block';
    }
    if(errors.hasOwnProperty('request')){
        document.querySelector('#error-request').innerText = errors.request;
        document.querySelector('#error-request').style.display = 'block';
    }
    for (const key in errors) {
        if (errors.hasOwnProperty(key)) {
            const error = errors[key];
            const productId = key; // L'ID du produit est la clé de l'erreur
            const errorElement = document.querySelector(`#error-quantity-${productId}`);
            if (errorElement) {
                errorElement.innerText = error;
                errorElement.style.display = 'block';
            }
        }
    }

}

//reset the error messages 
export function resetErrorMessages(){

    const errors = document.querySelectorAll('.error-message');
    errors.forEach(error => {
        error.innerText = '';
        error.style.display = 'none';
    });
    // //firstname

    // const firstname = document.querySelector('#error-firstname');
    // if(firstname){
    //     firstname.innerText = '';
    //     firstname.style.display = 'none';
    // }

    // //lastname
    // const lastname = document.querySelector('#error-lastname');
    // if(lastname){
    //     lastname.innerText = '';
    //     lastname.style.display = 'none';
    // }

    // //email
    // const email = document.querySelector('#error-email');
    // if(email){
    //     email.innerText = '';
    //     email.style.display = 'none';
    // }

    // //password
    // const password = document.querySelector('#error-password');
    // if(password){
    //     password.innerText = '';
    //     password.style.display = 'none';
    // }

    // //phone number
    // const phoneNumber = document.querySelector('#error-phone-number');
    // if(phoneNumber){
    //     phoneNumber.innerText = '';
    //     phoneNumber.style.display = 'none';
    // }

    // //blank
    // const blank = document.querySelector('#error-blank');
    // if(blank){
    //     blank.innerText = '';
    //     blank.style.display = 'none';
    // }

    // //confimation password
    // const confirmPassword = document.querySelector('#error-confirmation-password');
    // if(confirmPassword){
    //     confirmPassword.innerText = '';
    //     confirmPassword.style.display = 'none';
    // }

    // //street number
    // const streetNumber = document.querySelector('#error-street-number');
    // if(streetNumber){
    //     streetNumber.innerText = '';
    //     streetNumber.style.display = 'none';
    // }

    // //street number
    // const streetName = document.querySelector('#error-street-name');
    // if(streetName){
    //     streetName.innerText = '';
    //     streetName.style.display = 'none';
    // }

    // //zipcode
    // const zipcode = document.querySelector('#error-zipcode');
    // if(zipcode){
    //     zipcode.innerText = '';
    //     zipcode.style.display = 'none';
    // }

    // //city
    // const city = document.querySelector('#error-city');
    // if(city){
    //     city.innerText = '';
    //     city.style.display = 'none';
    // }

    // //product name
    // const productName = document.querySelector('#error-product-name');
    // if(productName){
    //     productName.innerText = '';
    //     productName.style.display = 'none';
    // }

    // //price
    // const price = document.querySelector('#error-price');
    // if(price){
    //     price.innerText = '';
    //     price.style.display = 'none';
    // }

    // //image type
    // const imageType = document.querySelector('#error-image-type');
    // if(imageType){
    //     imageType.innerText = '';
    //     imageType.style.display = 'none';
    // }

    // //image size
    // const imageSize = document.querySelector('#error-image-size');
    // if(imageSize){
    //     imageSize.innerText = '';
    //     imageSize.style.display = 'none';
    // }

    // //category
    // const category = document.querySelector('#error-category');
    // if(category){
    //     category.innerText = '';
    //     category.style.display = 'none';
    // }

    // //expiration date
    // const expirationDate = document.querySelector('#error-expiration-date');
    // if(expirationDate){
    //     expirationDate.innerText = '';
    //     expirationDate.style.display = 'none';
    // }

    // //paiement card number
    // const cardNumber = document.querySelector('#error-card-number');
    // if(cardNumber){
    //     cardNumber.innerText = '';
    //     cardNumber.style.display = 'none';
    // }

    // //the cvv
    // const cvv = document.querySelector('#error-cvv');
    // if(cvv){
    //     cvv.innerText = '';
    //     cvv.style.display = 'none';
    // }
}

//toggle the view of password in a password field
export function togglePasswordField(icon, passwordField) {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type); 
    if (icon.classList.contains('fa-regular')) {
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
    } else {
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
    }
}
//validate the field with some constraints
export function validateField(input, validatorFn, errorSelector, errorMessage) {
    input.addEventListener('change', () => {
        const isValid = validatorFn(input.value);
        if (!isValid) {
            Validator.displayError(errorSelector, errorMessage);
        } else {
            Validator.unDisplayError(errorSelector);
        }
    });
}

export function handleFormSubmit(form, url, successCallback){
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', url);
        xhr.onload = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = JSON.parse(xhr.responseText);
                if(response.success){
                    if(response.orderNumber){
                        localStorage.setItem("orderNumber", response.orderNumber);
                    }
                    if(response.message){
                        localStorage.setItem("successMessage",response.message);
                    }
                    successCallback(response);
                }
                if(response.errorMessage){
                    displayErrors(response.errorMessage);
                }
            }else{
                console.error(`Error: ${xhr.status}`);
            }
        };
        xhr.send(formData);
    });
}