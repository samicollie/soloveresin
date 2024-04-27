<div class="white-container">
    <h2>Paiement</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="order-payment-formular" action="/cart/order/payment" method="POST">
        <div id="error-blank" class="error-message none"></div>
        <input type="hidden" name="token_csrf" value="<?= $tokenCSRF ?>">
        <div class="input-block">
            <label for="card-number">Numéro de carte </label>
            <input type="text" id="card-number" name="card-number" placeholder="XXXX-XXXX-XXXX-XXXX" required>
            <div id="error-card-number" class="error-message none"></div>
        </div>
        <div class="input-block">
            <label for="lastname">Nom du titulaire </label>
            <input type="text" id="lastname" name="lastname" required>
            <div class="error-message none" id="error-lastname"></div>
        </div>
        <div class="input-block">
            <label for="expiration-date">Date d'expiration' </label>
            <input type="text" id="expiration-date" name="expiration-date" placeholder="MM/AA" required>
            <div id="error-expiration-date" class="error-message none"></div>
        </div>
        <div class="input-block">
            <label for="cvv">Cryptogramme (CVV) </label>
            <input type="text" id="cvv" name="cvv" required>
            <div id="error-cvv" class="error-message none"></div>
        </div>
        <div class="submit-block">
            <button type="submit" class="high-btn primary-btn">
                PAYER
            </button>
        </div>
    </form>
</div>