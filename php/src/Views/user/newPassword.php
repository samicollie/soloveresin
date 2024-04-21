<div class="white-container">
    <h3>Réinitialisation du mot de passe</h3>
    <div class="underline">
        <hr>
    </div>
    <form action="/newPassword" method="POST" id="new-password-formular">
        <input type="hidden" name="token" value="<?= $resetToken ?>">
        <div class="input-block">
            <label for="password">Nouveau mot de passe</label>
            <div class="input-password">
                <input type="password" name="password" id="password" required>
                <i class="fa-regular fa-eye eye-icon eye-password"></i>
            </div>
            <div class="error-message none" id="error-password"></div>
        </div>
        <div class="input-block">
            <label for="confirmation-password">Confirmation du mot de passe</label>
            <div class="input-password">
                <input type="password" name="confirmation-password" id="confirmation-password" required>
                <i class="fa-regular fa-eye eye-icon eye-confirmation-password"></i>
            </div>
            <div class="error-message none" id="error-confirmation-password"></div>
        </div>
        <div class="submit-block">
            <button type="submit" class="large-btn primary-btn">REINITIALISER</button>
        </div>
    </form>
</div>