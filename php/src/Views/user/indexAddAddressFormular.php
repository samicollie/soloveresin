<div class="white-container">
    <h3>Ajouter une adresse </h3>
    <div class="underline">
        <hr>
    </div>
    <form action="/profile/address/add" method="post">
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
        <div class="address-content">
            <div class="street-number">
                <label class="address-label" for="street-number">N°</label>
                <input type="text" name="street_number" id="street-number" class="address-input">
            </div>
            <div class="street-name">
                <label class="address-label" for="street-name">Nom de rue</label>
                <input type="text" name="street_name" id="street-name" class="address-input">
            </div>
        </div>
        <div class="address-content">
            <div class="zipcode">
                <label class="address-label" for="zipcode">Code postal</label>
                <input type="text" id="zipcode" name="zipcode" class="address-input">
            </div>
            <div class="city">
                <label class="address-label" for="city">Ville</label>
                <input type="text" id="city" name="city" class="address-input">
            </div>
        </div>
        <?php if(isset($errorMessage)): ?>
        <div class="error-message">
            <?= $errorMessage ?>
        </div>
        <?php endif ?>
        <div class="submit-block">
            <button type="submit" class="primary-btn save-btn">SAUVEGARDER</button>
        </div>
    </form>
</div>