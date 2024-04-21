<div class="white-container">
    <h3>Réinitialisation du mot de passe</h3>
    <div class="underline">
        <hr>
    </div>
    <?php if(isset($errorMessage)): ?>
    <div class="error-message">
        <?= $errorMessage ?>
    </div>
    <?php endif ?>
    <form id="reset-password" class="reset-password" action="/resetPassword" method="POST">
        <div id="success-message" class="success-message none"></div>
        <div class="input-block">
            <label for="email">Saisissez votre email</label>
            <input type="email" name="email">
        </div>
        <div class="error-message none" id="error-email"></div>
        <div class="submit-block">
            <button class="large-btn primary-btn" type="submit">ENVOYER</button>
        </div>
    </form>
</div>