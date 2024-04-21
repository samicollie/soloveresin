<div class="white-container">
    <h2>Générer un nouveau lien</h2>
    <div class="underline">
        <hr>
    </div>
    <?php if(isset($errorMessage)): ?>
    <div><?= $errorMessage ?></div>
    <?php endif ?>
    <form action="/generate/link" method="POST" id="generate-link-formular">
        <div class="input-block">
            <label for="email" name="email">Email</label>
            <input type="email" name="email" id="email" required>
            <div class="error-message none" id="error-email"></div>
            <div class="error-message none" id="error-blank"></div>
        </div>
        <div class="register-action-block">
            <button class="primary-btn high-btn">GENERER</button>
        </div>
    </form>
</div>