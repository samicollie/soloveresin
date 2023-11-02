<?php if(isset($successMessage)): ?>
<div class="success-message">
    <?= $successMessage ?>
</div>
<?php endif ?>
<div class="white-container login-form">
    <h3 class="form-title">S'identifier</h3>
    <div class="underline">
        <hr>
    </div>
    <form action="/login" method="post">
        <div class="input-block">
            <label for="email"> Email</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div class="input-block">
            <label for="password"> Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <?php if(isset($errorMessage)): ?>
        <div class="error-message">
        <?= $errorMessage ?>
        </div>
        <?php endif ?>
        <div class="register-action-block">
            <button class="register-btn primary-btn">SE CONNECTER</button>
        </div>
    </form>
</div>

<div class="white-container new-account">
    <a href="/register">
        <button class="primary-btn register-btn">CREER UN COMPTE</button>
    </a>
</div>