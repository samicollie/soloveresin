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
});