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

// At the loading of the page or when we get the data
document.addEventListener('DOMContentLoaded', () => {
    const ratingsElements = document.querySelectorAll('.stars-rating');

    ratingsElements.forEach(ratingElement => {
        const rating = parseFloat(ratingElement.getAttribute('data-rating'));
        displayRating(rating, ratingElement);
    });
});