<div class="white-container login-form">
    <h3 class="form-title">S'identifier</h3>
    <div class="underline">
        <hr>
    </div>
    <div id="success-message" class="success-message none"></div>
    <form id="login-formular" class="account-formular" action="/login" method="post">
        <div class="error-message none" id="error-email"></div>
        <div class="error-message none" id="error-blank"></div>
        <div class="input-block">
            <label for="email"> Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="input-block">
            <label for="password"> Mot de passe</label>
            <div class="input-password">
                <input type="password" name="password" id="password" required>
                <i class="fa-regular fa-eye eye-icon eye-password"></i>
            </div>
        </div>
        <div class="forgot-password"><a href="/resetPassword">Mot de passe oublié</a></div>
        <div class="register-action-block">
            <button class="high-btn primary-btn">SE CONNECTER</button>
        </div>
    </form>
</div>

<div class="white-container new-account">
    <a href="/register">
        <button class="primary-btn high-btn">CREER UN COMPTE</button>
    </a>
</div>