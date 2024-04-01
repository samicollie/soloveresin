import {Validator} from "./validator.js";
// At the loading of the page or when we get the data
document.addEventListener('DOMContentLoaded', () => {

    // function to display the rating depends on the rating of the product
    function displayRating(rating, element) {
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

    const ratingsElements = document.querySelectorAll('.stars-rating');

    ratingsElements.forEach(ratingElement => {
        const rating = parseFloat(ratingElement.getAttribute('data-rating'));
        displayRating(rating, ratingElement);
    });
        
    //listen search subittion in admin zone
    const searchInput = document.querySelector('#product-input-search');
    const submitSearchButton = document.querySelector('#product-search-btn')
    
    if(searchInput){
        // by clicking on the enter key
        searchInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                const content = searchInput.value;
                formData.append("search", content);
                const currentUrl = window.location.href;
                
                //custome the request url in function of the current
                let requestUrl;
                if(currentUrl.includes('/admin/')){
                    // on the admin side
                    requestUrl = "/admin/products/list"; 
                }else{
                    // on the simple user side
                    requestUrl = "/store";
                }
                xhr.open("POST", requestUrl);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        const data = xhr.responseText;
                        //console.log(data);
                        document.querySelector('#content-to-replaced').innerHTML = data;
                    } else {
                        console.error(`Error: ${xhr.status}`);
                    }
                };
                xhr.send(new URLSearchParams(formData));
            }
        });
        
        //by using the submit button
        submitSearchButton.addEventListener('click', (event) => {
            const xhr = new XMLHttpRequest();
            const formData = new FormData();
            const content = searchInput.value;
            formData.append("search", content);
            const currentUrl = window.location.href;
            
            //custome the request url in function of the current
            let requestUrl;
            if(currentUrl.includes('/admin/')){
                // on the admin side
                requestUrl = "/admin/products/list"; 
            }else{
                // on the simple user side
                requestUrl = "/store";
            }
            xhr.open("POST", requestUrl);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const data = xhr.responseText;
                    //console.log(data);
                    document.querySelector('#content-to-replaced').innerHTML = data;
                } else {
                    console.error(`Error: ${xhr.status}`);
                }
            };
            xhr.send(new URLSearchParams(formData));
        });
    }    

    // request adding product in a cart.
    const addButtons = document.querySelectorAll('.add-cart-btn');

    if(addButtons){
        addButtons.forEach(btn =>{
            btn.addEventListener('click', (event)=>{
                event.preventDefault();
                const form = btn.parentNode;
                const productId = form.querySelector('.product-id').value;

                // AJAX request
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append("product_id", productId);
                formData.append("current_url", window.location.href);
                xhr.open("POST", "/cart/add");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = () => {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        const data = xhr.responseText;
                        document.querySelector('#cart-link').innerHTML = data;
                    } else {
                        console.error(`Error: ${xhr.status}`);
                    }
                };
                xhr.send(new URLSearchParams(formData));
            })
        });
    }

    //manage the register-formular 
    const form = document.querySelector('#register-formular');
    if(form){
        const firstnameInput = form.querySelector('input[name="firstname"]');
        firstnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateFirstname(firstnameInput.value);
            if(!isValid){
                Validator.displayError("#error-firstname", Validator.firstname);
            }else{
                Validator.unDisplayError("#error-firstname");
            }
        });

        const lastnameInput = form.querySelector('input[name="lastname"]');
        lastnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateFirstname(lastnameInput.value);
            if(!isValid){
                Validator.displayError("#error-lastname", Validator.lastname);
            }else{
                Validator.unDisplayError("#error-lastname");
            }
        });

        const emailInput = form.querySelector('input[name="email"]');
        emailInput.addEventListener('change', ()=>{
            const isValid = Validator.validateEmail(emailInput.value);
            if(!isValid){
                Validator.displayError("#error-email", Validator.email);
            }else{
                Validator.unDisplayError("#error-email");
            }
        });

        const passwordInput = form.querySelector('input[name="password"]');
        passwordInput.addEventListener('change', ()=>{
            const isValid = Validator.validatePassword(passwordInput.value);
            if(!isValid){
                Validator.displayError("#error-password", Validator.password);
            }else{
                Validator.unDisplayError("#error-password");
            }
        });

        const confimationPasswordInput = form.querySelector('input[name="confirmation-password"]');
        confimationPasswordInput.addEventListener('change', ()=>{
            const isValid = Validator.validateConfirmationPassword(confimationPasswordInput.value);
            if(!isValid){
                Validator.displayError("#error-confirmation-password", Validator.confirmPassword);
            }else{
                Validator.unDisplayError("#error-confirmation-password");
            }
        });

        form.addEventListener('submit', (event)=>{
            event.preventDefault();
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/register');
            xhr.onload = () => {
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        window.location.href= '/register/success';
                    }else{
                        //display the error messages
                        displayErrors(response.errorMessage);
                    }
                }
            };
            //send the request
            xhr.send(formData);
        });
    }

    //function displaying success messages
    function displaySuccess(success){
        //reset the success message
        resetSuccessMessage();

        //display the message
        document.querySelector('#success-message').innerText = success;
        document.querySelector('#success-message').style.display = 'block';
    }

    //reset the success message.
    function resetSuccessMessage(){
        const success = document.querySelector('#success-message');
        if(success){
            success.innerText = '';
            success.style.display = 'none';
        }
    }

    //function displaying error messages into the formular
    function displayErrors(errors){
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


    }

    //reset the error messages 
    function resetErrorMessages(){
        //firstname

        const firstname = document.querySelector('#error-firstname');
        if(firstname){
            firstname.innerText = '';
            firstname.style.display = 'none';
        }

        //lastname
        const lastname = document.querySelector('#error-lastname');
        if(lastname){
            lastname.innerText = '';
            lastname.style.display = 'none';
        }

        //email
        const email = document.querySelector('#error-email');
        if(email){
            email.innerText = '';
            email.style.display = 'none';
        }

        //password
        const password = document.querySelector('#error-password');
        if(password){
            password.innerText = '';
            password.style.display = 'none';
        }

        //phone number
        const phoneNumber = document.querySelector('#error-phone-number');
        if(phoneNumber){
            phoneNumber.innerText = '';
            phoneNumber.style.display = 'none';
        }

        //blank
        const blank = document.querySelector('#error-blank');
        if(blank){
            blank.innerText = '';
            blank.style.display = 'none';
        }

        //confimation password
        const confirmPassword = document.querySelector('#error-confirmation-password');
        if(confirmPassword){
            confirmPassword.innerText = '';
            confirmPassword.style.display = 'none';
        }

        //street number
        const streetNumber = document.querySelector('#error-street-number');
        if(streetNumber){
            streetNumber.innerText = '';
            streetNumber.style.display = 'none';
        }

        //street number
        const streetName = document.querySelector('#error-street-name');
        if(streetName){
            streetName.innerText = '';
            streetName.style.display = 'none';
        }

        //zipcode
        const zipcode = document.querySelector('#error-zipcode');
        if(zipcode){
            zipcode.innerText = '';
            zipcode.style.display = 'none';
        }

        //city
        const city = document.querySelector('#error-city');
        if(city){
            city.innerText = '';
            city.style.display = 'none';
        }

        //product name
        const productName = document.querySelector('#error-product-name');
        if(productName){
            productName.innerText = '';
            productName.style.display = 'none';
        }

        //price
        const price = document.querySelector('#error-price');
        if(price){
            price.innerText = '';
            price.style.display = 'none';
        }

        //image type
        const imageType = document.querySelector('#error-image-type');
        if(imageType){
            imageType.innerText = '';
            imageType.style.display = 'none';
        }

        //image size
        const imageSize = document.querySelector('#error-image-size');
        if(imageSize){
            imageSize.innerText = '';
            imageSize.style.display = 'none';
        }

        //category
        const category = document.querySelector('#error-category');
        if(category){
            category.innerText = '';
            category.style.display = 'none';
        }
    }

    //manage the generate link formular
    const generateLinkFormular = document.querySelector('#generate-link-formular');
    if(generateLinkFormular){
        generateLinkFormular.addEventListener('submit', event =>{
            event.preventDefault();
            const formData = new FormData(generateLinkFormular);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/generate/link');
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        window.location.href = '/generate/link/confirmation';
                    }
                    if(response.errorMessage){
                        displayErrors(response.errorMessage);
                    }
                    if(response.isVerified){
                        window.location.href = '/login';
                    }
                }
            }
            xhr.send(formData);
        });
    }

    //manage the login formular
    const loginForm = document.querySelector('#login-formular');
    if(loginForm){
        const emailInput = loginForm.querySelector('input[name="email"]');
        emailInput.addEventListener('change', ()=>{
            const isValid = Validator.validateEmail(emailInput.value);
            if(!isValid){
                Validator.displayError("#error-email", Validator.email);
            }else{
                Validator.unDisplayError("#error-email");
            }
        });

        loginForm.addEventListener('submit', (event)=>{
            event.preventDefault();
            const formData = new FormData(loginForm);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/login', true);
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        window.location.href = '/profile';
                    }
                    if(response.errorMessage){
                        console.log(response.errorMessage);
                        displayErrors(response.errorMessage);
                    }
                }
            };
            xhr.send(formData);
        });
    }

    //manage modify contact form
    const modifyContactForm = document.querySelector('#modify-contact-formular');
    if(modifyContactForm){
        const firstnameInput = modifyContactForm.querySelector('input[name="firstname"]');
        firstnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateFirstname(firstnameInput.value);
            if(!isValid){
                Validator.displayError("#error-firstname", Validator.firstname);
            }else{
                Validator.unDisplayError("#error-firstname");
            }
        });

        const lastnameInput = modifyContactForm.querySelector('input[name="lastname"]');
        lastnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateLastname(lastnameInput.value);
            if(!isValid){
                Validator.displayError("#error-lastname", Validator.lastname);
            }else{
                Validator.unDisplayError("#error-lastname");
            }
        });

        const emailInput = modifyContactForm.querySelector('input[name="email"]');
        emailInput.addEventListener('change', ()=>{
            const isValid = Validator.validateEmail(emailInput.value);
            if(!isValid){
                Validator.displayError("#error-email", Validator.email);
            }else{
                Validator.unDisplayError("#error-email");
            }
        });

        const phoneNumberInput = modifyContactForm.querySelector('input[name="phone-number"]');
        phoneNumberInput.addEventListener('change', ()=>{
            const isValid = Validator.validatePhoneNumber(phoneNumberInput.value);
            if(!isValid){
                Validator.displayError("#error-phone-number", Validator.phoneNumber);
            }else{
                Validator.unDisplayError("#error-phone-number");
            }
        });

        modifyContactForm.addEventListener('submit', (event) =>{
            event.preventDefault();

            const formData = new FormData(modifyContactForm);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/profile/contact/modify', true);
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        window.location.href = '/profile';
                    }
                    if(response.errorMessage){
                        displayErrors(response.errorMessage);
                    }
                }
            };
            xhr.send(formData);

        });
    }

    //manage address formular
    const addressForm = document.querySelector('#address-formular');
    if(addressForm){
        const firstnameInput = addressForm.querySelector('input[name="firstname"]');
        firstnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateFirstname(firstnameInput.value);
            if(!isValid){
                Validator.displayError("#error-firstname", Validator.firstname);
            }else{
                Validator.unDisplayError("#error-firstname");
            }
        });

        const lastnameInput = addressForm.querySelector('input[name="lastname"]');
        lastnameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateLastname(lastnameInput.value);
            if(!isValid){
                Validator.displayError("#error-lastname", Validator.lastname);
            }else{
                Validator.unDisplayError("#error-lastname");
            }
        });

        const streetNumberInput = addressForm.querySelector('input[name="street_number"]');
        streetNumberInput.addEventListener('change', ()=>{
            const isValid = Validator.validateStreetNumber(streetNumberInput.value);
            if(!isValid){
                Validator.displayError("#error-street-number", Validator.streetNumber);
            }else{
                Validator.unDisplayError("#error-street-number");
            }
        });

        const streetNameInput = addressForm.querySelector('input[name="street_name"]');
        streetNameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateStreetName(streetNameInput.value);
            if(!isValid){
                Validator.displayError("#error-street-name", Validator.streetName);
            }else{
                Validator.unDisplayError("#error-street-name");
            }
        });

        const zipcodeInput = addressForm.querySelector('input[name="zipcode"]');
        zipcodeInput.addEventListener('change', ()=>{
            const isValid = Validator.validateZipcode(zipcodeInput.value);
            if(!isValid){
                Validator.displayError("#error-zipcode", Validator.zipcode);
            }else{
                Validator.unDisplayError("#error-zipcode");
            }
        });

        const cityInput = addressForm.querySelector('input[name="city"]');
        addressForm.addEventListener('change', ()=>{
            const isValid = Validator.validateCity(cityInput.value);
            if(!isValid){
                Validator.displayError("#error-city", Validator.city);
            }else{
                Validator.unDisplayError("#error-city");
            }
        });

        addressForm.addEventListener('submit', (event)=>{
            event.preventDefault();
            const formData = new FormData(addressForm);
            const xhr = new XMLHttpRequest();
            let url;
            if(addressForm.classList.contains('add-address-formular')){
                url = "/profile/address/add";
            }
            if(addressForm.classList.contains('modify-address-formular')){
                url = "/profile/address/modify";
            }
            xhr.open('POST', url, true);
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        window.location.href = '/profile';
                    }
                    if(response.errorMessage){
                        console.log(response.errorMessage);
                        displayErrors(response.errorMessage);
                    }
                }
            };
            xhr.send(formData);

        });
    }

    //manage admin product formular
    const adminProductForm = document.querySelector('#admin-product-formular');
    if(adminProductForm){
        const productNameInput = adminProductForm.querySelector('input[name="name"]');
        productNameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateProductName(productNameInput.value);
            if(!isValid){
                Validator.displayError("#error-product-name", Validator.productName);
            }else{
                Validator.unDisplayError("#error-product-name");
            }
        });

        const priceInput = adminProductForm.querySelector('input[name="price"]');
        priceInput.addEventListener('change', ()=>{
            const isValid = Validator.validatePrice(priceInput.value);
            if(!isValid){
                Validator.displayError("#error-price", Validator.price);
            }else{
                Validator.unDisplayError("#error-price");
            }
        });

        adminProductForm.addEventListener('submit', (event)=>{
            event.preventDefault();
            const formData = new FormData(adminProductForm);
            const xhr = new XMLHttpRequest();
            let url;
            if(adminProductForm.classList.contains('add-product-formular')){
                url = "/admin/products/add";
            }
            if(adminProductForm.classList.contains('modify-product-formular')){
                url = "/admin/products/modify";
            }
            xhr.open('POST', url, true);
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        localStorage.setItem("successMessage", response.successMessage);
                        window.location.href = '/admin/dashboard';
                    }
                    if(response.errorMessage){
                        displayErrors(response.errorMessage);
                    }
                }
            };
            xhr.send(formData);
        });
    }

    //display message of success
    if(localStorage.getItem("successMessage")){
        const successMessage = localStorage.getItem("successMessage");
        displaySuccess(successMessage);
        localStorage.removeItem("successMessage");
    }

    //manage admin category formular
    const adminCategoryForm = document.querySelector('#admin-category-formular');
    if(adminCategoryForm){
        const categoryNameInput = adminCategoryForm.querySelector("input[name='name']");
        categoryNameInput.addEventListener('change', ()=>{
            const isValid = Validator.validateCategoryName(categoryNameInput.value);
            if(!isValid){
                Validator.displayError("#error-category", Validator.categoryName);
            }else{
                Validator.unDisplayError("#error-category");
            }
        });
        adminCategoryForm.addEventListener('submit', (event)=>{
            event.preventDefault();
            const formData = new FormData(adminCategoryForm);
            const xhr = new XMLHttpRequest();
            let url;
            if(adminCategoryForm.classList.contains('add-category-formular')){
                url = "/admin/category/add";
            }
            if(adminCategoryForm.classList.contains('modify-category-formular')){
                url = "/admin/category/modify";
            }
            xhr.open('POST', url, true);
            xhr.onload = ()=>{
                if(xhr.readyState == 4 && xhr.status == 200){
                    const response = JSON.parse(xhr.responseText);
                    if(response.success){
                        localStorage.setItem("successMessage", response.successMessage);
                        window.location.href = '/admin/dashboard';
                    }
                    if(response.errorMessage){
                        displayErrors(response.errorMessage);
                    }
                }
            };
            xhr.send(formData);
        });
    }
});