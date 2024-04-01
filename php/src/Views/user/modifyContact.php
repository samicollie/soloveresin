<div class="white-container">
    <h3>Modifier mon profil</h3>
    <div class="underline">
        <hr>
    </div>
    <form id="modify-contact-formular" action="/profile/contact/modify" method="post">
        <div class="info-content info-content-modify">
            <input type="text" name="firstname" class="identity identity-input" value="<?= $user->firstname ?>">
            <input type="text" name="lastname" class="identity identity-input" value="<?= $user->lastname ?>">
        </div>
        <div class="error-message none" id="error-firstname"></div>
        <div class="error-message none" id="error-lastname"></div>
        <div class="contact-details contact-details-modify">
            <h4>Coordonnées : </h4>
            <p>
                <label for="email">Email : </label>
                <input type="email" id="email" name="email" value="<?= $user->email ?>">
                <div class="error-message none" id="error-email"></div>
            </p>
            <p class="phone-number">
                <label for="phone_number">Tel : </label>
                <input type="text" id="phone_number" name="phone-number" value="<?= $user->phone_number ?>">
                <div class="error-message none" id="error-phone-number"></div>
            </p>
        </div>
        <div class="submit-block">
            <button type="submit" class="primary-btn save-btn">SAUVEGARDER</button>
        </div>
    </form>
</div>