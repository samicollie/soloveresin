import {Validator} from "./validator.js";
import {nextSlide, displaySuccess, displayErrors,displayRating,sendAjaxRequest, togglePasswordField, handleSearch, handleFormSubmit, validateField, resetErrorMessages} from './functions.js';
// At the loading of the page or when we get the data
document.addEventListener('DOMContentLoaded', () => {

    //toggle hamburger menu
    const tabHamburger = document.querySelector("#tab-hamburger-icon");
    if(tabHamburger){
        const hamburgerMenu = document.querySelector('.hamburger-menu');
        const main = document.querySelector('main');
        let previousTabIndex = -1;
        //get all tabs in nav mobile
        const tabsNavMobile = document.querySelectorAll('.nav-mobile a');
        tabHamburger.addEventListener('click', ()=>{
            let isTabHamburgerSelected = tabHamburger.classList.contains('selected-tab');
            if(isTabHamburgerSelected){
                hamburgerMenu.style.display = 'none';
                main.classList.remove('none');
                if (previousTabIndex !== -1) {
                    //select the tab which is selected before click on hamburger
                    tabsNavMobile[previousTabIndex].querySelector('span').classList.add('selected-tab');
                }
                tabHamburger.classList.remove('selected-tab');
            }else{
                hamburgerMenu.style.display = 'block';
                main.classList.add('none');
                previousTabIndex = -1;
                tabsNavMobile.forEach((tab, index) => {
                    if (tab.querySelector('span').classList.contains('selected-tab')) {
                        previousTabIndex = index;
                        tab.querySelector('span').classList.remove('selected-tab');
                    }
                });
                tabHamburger.classList.add('selected-tab');
            }
        });
    }

    const currentUrl = window.location.href;
    const ratingsElements = document.querySelectorAll('.stars-rating');

    sendAjaxRequest('/session/update', "GET", [], false, ()=>{});

    ratingsElements.forEach(ratingElement => {
        const rating = parseFloat(ratingElement.getAttribute('data-rating'));
        displayRating(rating, ratingElement);
    });


    //start the carousel on the landing page
    if(document.querySelector('.carousel-container')){
        //start the carousel
        globalThis.currentIndex = 0;
        globalThis.direction = 1;
        const carouselImages = document.querySelectorAll('.carousel-slide');
        const carouselSlide = document.querySelector('.carousel-container');
        const slideWidth = carouselImages[0].clientWidth;
        
        setInterval(() => {
            nextSlide(carouselImages, carouselSlide, slideWidth)
        }, 3000);
    }
        
    //listen search submit in admin area or in store
    const searchInput = document.querySelector('#product-input-search');
    const submitSearchButton = document.querySelector('#product-search-btn')
    
    if(searchInput){
        // by clicking on the enter key
        searchInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                handleSearch(searchInput, window.location.href);
            }
        });
        
        //by using the submit button
        submitSearchButton.addEventListener('click', (event) => {
            handleSearch(searchInput, window.location.href);            
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
                const formData = new FormData();
                formData.append("product_id", productId);
                sendAjaxRequest("/cart/add","POST", formData, true,(response)=>{
                    if(response.productCounter){
                        document.querySelector('.product-counter').innerText = response.productCounter;
                        resetErrorMessages();
                    }
                    if(response.errorMessage){
                        displayErrors(response.errorMessage);
                    }
                });
            })
        });
    }

    //handle the register-formular 
    const form = document.querySelector('#register-formular');
    if(form){
        //handle the view of password to click on icon
        const tooglePassword = document.querySelector('.eye-password');
        const toogleConfirmationPassword = document.querySelector('.eye-confirmation-password');
        const passwordField = document.querySelector('#password');
        const confirmationPasswordField = document.querySelector('#confirmation-password');
        tooglePassword.addEventListener('click', (event)=>{
            togglePasswordField(event.currentTarget, passwordField);
        });
        toogleConfirmationPassword.addEventListener('click', ()=>{
            togglePasswordField(event.currentTarget, confirmationPasswordField);
        });

        //validate the fields of the formular to respect the constraints
        const firstnameInput = form.querySelector('input[name="firstname"]');
        const lastnameInput = form.querySelector('input[name="lastname"]');
        const emailInput = form.querySelector('input[name="email"]');
        const passwordInput = form.querySelector('input[name="password"]');
        const confimationPasswordInput = form.querySelector('input[name="confirmation-password"]');
        validateField(firstnameInput, Validator.validateFirstname, "#error-firstname", Validator.firstname);
        validateField(lastnameInput, Validator.validateLastname, "#error-lastname", Validator.lastname);
        validateField(emailInput, Validator.validateEmail, "#error-email", Validator.email);
        validateField(passwordInput, Validator.validatePassword, "#error-password", Validator.password);
        validateField(confimationPasswordInput, Validator.validateConfirmationPassword, "#error-confirmation-password", Validator.confirmPassword);

        handleFormSubmit(form, "/register", ()=>{
                window.location.href= '/register/success';
        });
    }

    //handle the generate link formular
    const generateLinkFormular = document.querySelector('#generate-link-formular');
    if(generateLinkFormular){
        handleFormSubmit(generateLinkFormular, '/generate/link', ()=>{
            window.location.href = '/generate/link/confirmation';
        });
    }

    //handle new password formular
    const newPasswordForm = document.querySelector('#new-password-formular');
    if(newPasswordForm){
        //manage the view of password to click on icon
        const passwordField = document.querySelector('#password');
        const confirmationPasswordField = document.querySelector('#confirmation-password');
        const togglePassword = document.querySelector('.eye-password');
        const toggleConfirmationPassword = document.querySelector('.eye-confirmation-password');
        togglePassword.addEventListener('click', (event)=>{
            togglePasswordField(event.currentTarget, passwordField);
        });
        toggleConfirmationPassword.addEventListener('click', (event)=>{
            togglePasswordField(event.currentTarget, confirmationPasswordField);
        });

        //validate the fields of the formular to respect the constraints
        const passwordInput = newPasswordForm.querySelector('input[name="password"]');
        const confirmationPasswordInput = newPasswordForm.querySelector('input[name="confirmation-password"]');
        validateField(passwordInput, Validator.validatePassword, "#error-password", Validator.password);
        validateField(confirmationPasswordInput, Validator.validateConfirmationPassword, "#error-confirmation-password", Validator.confirmPassword);
        
        handleFormSubmit(newPasswordForm, '/newPassword',()=>{
            window.location.href = '/login';
        });
    }

    //handle the login formular
    const loginForm = document.querySelector('#login-formular');
    if(loginForm){
        //manage the view of password to click on icon
        const passwordField = document.querySelector('#password');
        const togglePassword = document.querySelector('.eye-password');
        togglePassword.addEventListener('click', (event)=>{
            togglePasswordField(event.currentTarget, passwordField);
        });

        //validate the field of the formular to respect the constraints
        const emailInput = loginForm.querySelector('input[name="email"]');
        validateField(emailInput, Validator.validateEmail, "#error-email", Validator.email);

        handleFormSubmit(loginForm, '/login', ()=>{
            const previousPage = document.referrer;
            if(previousPage.includes('/cart')){
                window.location.href = '/cart/order/chooseaddresses';
            }else{
                window.location.href = '/profile';
            }
        });
    }

    //reset password formular 
    const resetPasswordForm = document.querySelector('#reset-password');
    if(resetPasswordForm){
        handleFormSubmit(resetPasswordForm, '/resetPassword', ()=>{
            window.location.href = '/resetPassword';
        });
    }

    //handle modify contact formular
    const modifyContactForm = document.querySelector('#modify-contact-formular');
    if(modifyContactForm){
        //validate the fields of the formular to respect the constraints
        const firstnameInput = modifyContactForm.querySelector('input[name="firstname"]');
        validateField(firstnameInput, Validator.validateFirstname, "#error-firstname", Validator.firstname);
        const lastnameInput = modifyContactForm.querySelector('input[name="lastname"]');
        validateField(lastnameInput, Validator.validateLastname, "#error-lastname", Validator.lastname);
        const phoneNumberInput = modifyContactForm.querySelector('input[name="phone-number"]');
        validateField(phoneNumberInput, Validator.validatePhoneNumber, "#error-phone-number", Validator.phoneNumber);
        
        handleFormSubmit(modifyContactForm, '/profile/contact/modify',()=>{
            window.location.href = '/profile';
        });
    }

    //handle address formular
    const addressForm = document.querySelector('#address-formular');
    if(addressForm){
        //validate the fields of the formular to respect the constraints
        const firstnameInput = addressForm.querySelector('input[name="firstname"]');
        validateField(firstnameInput,Validator.validateFirstname, "#error-firstname", Validator.firstname);
        const lastnameInput = addressForm.querySelector('input[name="lastname"]');
        validateField(lastnameInput, Validator.validateLastname, "#error-lastname", Validator.lastname);
        const streetNumberInput = addressForm.querySelector('input[name="street-number"]');
        validateField(streetNumberInput, Validator.validateStreetNumber, "#error-street-number", Validator.streetNumber);
        const streetNameInput = addressForm.querySelector('input[name="street-name"]');
        validateField(streetNameInput, Validator.validateStreetName, "#error-street-name", Validator.streetName);
        const zipcodeInput = addressForm.querySelector('input[name="zipcode"]');
        validateField(zipcodeInput, Validator.validateZipcode, "#error-zipcode", Validator.zipcode);
        const cityInput = addressForm.querySelector('input[name="city"]');
        validateField(cityInput, Validator.validateCity, "#error-city", Validator.city);

        function defineUrl(currentUrl){
            if(currentUrl.endsWith('add') || currentUrl.endsWith('order')){
                return '/profile/address/add';
            }else{
                return '/profile/address/modify';
            }
        }
        handleFormSubmit(addressForm, defineUrl(currentUrl), (response)=>{
            const previousPage = document.referrer;
            if(previousPage.includes('/cart/order')){
                window.location.href = '/cart/order/chooseaddresses';
            }else{
                window.location.href = '/profile';
            }
        });
    }

    //handle admin product formular
    const adminProductForm = document.querySelector('#admin-product-formular');
    if(adminProductForm){
        //validate the fields of the formular to respect the constraints
        const productNameInput = adminProductForm.querySelector('input[name="name"]');
        validateField(productNameInput, Validator.validateProductName, "#error-product-name", Validator.productName)
        const priceInput = adminProductForm.querySelector('input[name="price"]');
        validateField(priceInput, Validator.validatePrice,"#error-price", Validator.price);

        const url = currentUrl.endsWith('add') ? '/admin/products/add' : '/admin/products/modify';
        handleFormSubmit(adminProductForm, url, ()=>{
            window.location.href = '/admin/dashboard';
        });
    }

    //display message of success
    if(localStorage.getItem("successMessage")){
        const successMessage = localStorage.getItem("successMessage");
        displaySuccess(successMessage);
        localStorage.removeItem("successMessage");
    }

    //display the order number in the confirmation page
    if(localStorage.getItem("orderNumber")){
        const orderNumber = localStorage.getItem("orderNumber");
        document.querySelector('#order-number').innerText = orderNumber;
        localStorage.removeItem("orderNumber");
    }

    //handle admin category formular
    const adminCategoryForm = document.querySelector('#admin-category-formular');
    if(adminCategoryForm){
        //validate the field of the formular to respect the constraints
        const categoryNameInput = adminCategoryForm.querySelector("input[name='category']");
        validateField(categoryNameInput, Validator.validateCategoryName, "#error-category", Validator.categoryName);
        
        const url = currentUrl.endsWith('add') ? '/admin/category/add' : '/admin/category/modify';
        handleFormSubmit(adminCategoryForm, url, ()=>{
            window.location.href = '/admin/dashboard';
        });
    }

    //handle the terms of sale formular
    const termsOfSaleForm = document.querySelector('#terms-of-sale-formular');
    if(termsOfSaleForm){
        handleFormSubmit(termsOfSaleForm, '/admin/legalNotices/termsofsale',()=>{
            window.location.href = '/admin/legalNotices';
        });
    }

    //handle privacy policy formular
    const privacyPolicyForm = document.querySelector('#privacy-policy-formular');
    if(privacyPolicyForm){
        handleFormSubmit(privacyPolicyForm, '/admin/legalNotices/privacypolicy',()=>{
            window.location.href = '/admin/legalNotices';
        });
    }

    //handle usage cookie formular
    const usageCookieForm = document.querySelector('#usage-cookie-formular');
    if(usageCookieForm){
        handleFormSubmit(usageCookieForm, '/admin/legalNotices/usagecookie',()=>{
            window.location.href = '/admin/legalNotices';
        });
    }

    //handle contact formular
    const contactForm = document.querySelector('#contact-formular');
    if(contactForm){
        handleFormSubmit(contactForm, '/contactUs',()=>{
            window.location.href = '/contactUs';
        });
    }

    //handle modify landing page formular
    const modifyLandingPageForm = document.querySelector('#admin-announces-formular');
    if(modifyLandingPageForm){
        handleFormSubmit(modifyLandingPageForm, '/admin/announces/modify',()=>{
            window.location.href = '/admin/dashboard';
        });
    }

    //handle the addresses choice formular
    const addressesChoiceForm = document.querySelector('#addresses-choice-formular');
    if(addressesChoiceForm){
        handleFormSubmit(addressesChoiceForm, '/cart/order/chooseaddresses',()=>{
            window.location.href = '/cart/order/summary';
        });
    }

    //handle the order summary formular
    const orderSummaryForm = document.querySelector('#order-summary-formular');
    if(orderSummaryForm){
        handleFormSubmit(orderSummaryForm, '/cart/order/summary',()=>{
            window.location.href = '/cart/order/payment';
        });
    }

    //handle the payment formular
    const orderPayementForm = document.querySelector('#order-payment-formular');
    if(orderPayementForm){
        handleFormSubmit(orderPayementForm, '/cart/order/payment',()=>{
            window.location.href = '/cart/order/confirmation';
        });
    }
});