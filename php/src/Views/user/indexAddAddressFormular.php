<div class="white-container">
    <h3>Ajouter une adresse </h3>
    <div class="underline">
        <hr>
    </div>
    <form id="address-formular" class="add-address-formular" action="/profile/address/add" method="post">
        <div class="error-message none" id="error-blank"></div>
        <div class="address-content">
            <div class="address-input-block">
                <label class="address-label" for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" class="address-input">
            </div>
            <div class="address-input-block">
                <label class="address-label" for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname" class="address-input">
            </div>
        </div>
        <div class="error-message none" id="error-firstname"></div>
        <div class="error-message none" id="error-lastname"></div>
        <div class="address-content">
            <div class="street-number">
                <label class="address-label" for="street-number">N°</label>
                <input type="text" name="street-number" id="street-number" class="address-input">
            </div>
            <div class="street-name">
                <label class="address-label" for="street-name">Nom de rue</label>
                <input type="text" name="street-name" id="street-name" class="address-input">
            </div>
        </div>
        <div class="error-message none" id="error-street-number"></div>
        <div class="error-message none" id="error-street-name"></div>
        <div class="address-content">
            <div class="zipcode">
                <label class="address-label" for="zipcode">Code postal</label>
                <input type="text" id="zipcode" name="zipcode" class="address-input">
                <div class="error-message none" id="error-zipcode"></div>
            </div>
            <div class="city">
                <label class="address-label" for="city">Ville</label>
                <input type="text" id="city" name="city" class="address-input">
                <div class="error-message none" id="error-city"></div>
            </div>
        </div>
        <div class="submit-block">
            <button type="submit" class="primary-btn large-btn">SAUVEGARDER</button>
            <a href="/profile">
                <button class="primary-btn delete-btn" type="button">ANNULER</button>
            </a>
        </div>
    </form>
</div>