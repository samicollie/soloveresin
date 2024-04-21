<div class="white-container">
    <div class="account-verify">
        <?php if(isset($successMessage)): ?>
        <h3><?= $successMessage ?></h3>
        <a href="/login" ><button class="primary-btn large-btn">SE CONNECTER</button></a>
        <?php endif ?>
        <?php if(isset($errorMessage)): ?>
        <h3><?= $errorMessage ?></h3>
        <a href="/generate/link"><button class="primary-btn large-btn">GENERER UN NOUVEAU LIEN</button></a>
        <?php endif ?>
    </div>
</div>