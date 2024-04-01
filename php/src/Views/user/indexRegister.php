<div class="white-container">
    <h3 class="form-title">Créer un compte</h3>
    <div class="underline">
        <hr>
    </div>
    <form class="account-formular" id="register-formular" action="/register" method="post">
        <div class="input-block">
            <label for="firstname"> Prénom</label>
            <input type="text" name="firstname" id="firstname" required>
            <div class="error-message none" id="error-firstname"></div>
        </div>
        <div class="input-block">
            <label for="lastname"> Nom</label>
            <input type="text" name="lastname" id="lastname" required>
            <div class="error-message none" id="error-lastname"></div>
        </div>
        <div class="input-block">
            <label for="email"> Email</label>
            <input type="text" name="email" id="email" required>
            <div class="error-message none" id="error-email"></div>
        </div>
        <div class="input-block">
            <label for="password"> Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <div class="error-message none" id="error-password"></div>
        </div>
        <div class="input-block">
            <label for="confirmation-password">Confirmation du mot de passe</label>
            <input type="password" name="confirmation-password" id="confirmation-password" required>
            <div class="error-message none" id="error-confirmation-password"></div>
        </div>
        <div class="error-message none" id="error-blank"></div>
        <div class="register-action-block">
            <button class="register-btn primary-btn">S'ENREGISTRER</button>
        </div>
    </form>
</div>