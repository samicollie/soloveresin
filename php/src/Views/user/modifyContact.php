<div class="white-container">
    <h3>Modifier mon profil</h3>
    <div class="underline">
        <hr>
    </div>
    <form action="/profile/contact/modify" method="post">
        <div class="info-content info-content-modify">
            <input type="text" name="firstname" class="identity identity-input" value="<?= $user->firstname ?>">
            <input type="text" name="lastname" class="identity identity-input" value="<?= $user->lastname ?>">
        </div>
        <div class="contact-details contact-details-modify">
            <h4>Coordonnées : </h4>
            <p>
                <label for="email">Email : </label>
                <input type="email" id="email" name="email" value="<?= $user->email ?>">
            </p>
            <p class="phone-number">
                <label for="phone_number">Tel : </label>
                <input type="text" id="phone_number" name="phone_number" value="<?= $user->phone_number ?>">
            </p>
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