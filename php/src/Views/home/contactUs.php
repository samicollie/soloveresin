<div class="white-container">
    <h2>Nous contacter</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="contact-formular" action="/contactUs" method="POST">
        <div id="success-message" class="success-message none"></div>
        <div id="error-blank" class="error-message none"></div>
        <div class="input-block">
            <label for="email">Votre email</label>
            <input type="email" name="email" id="email">
            <div id="error-mail" class="error-message none"></div>
        </div>
        <div class="input-block">
            <label for="title">Sujet de la demande</label>
            <input type="text" name="title" id="title">
        </div>
        <div class="input-block">
            <label for="message">Saisissez votre demande</label>
            <textarea name="message" id="message"></textarea>
        </div>
        <div class="submit-block">
            <button type="submit" class="primary-btn large-btn">ENVOYER</button>
        </div>
    </form>
</div>