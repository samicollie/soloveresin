<div class="white-container">
    <h2>Choix de l'adresse</h2>
    <div class="underline">
        <hr>
    </div>
    <div class="choice-add-address">
        <a href="/profile/address/add">
            <button class="primary-btn high-btn">AJOUTER UNE ADRESSE</button>
        </a>
    </div>
    <div id="error-blank" class="error-message none"></div>
    <form id="addresses-choice-formular" action="/cart/order/chooseaddresses" method="POST">
        <input type="hidden" name="token_csrf" value="<?= $tokenCSRF ?>">
        <div class="addresses-block">
            <fieldset>
                <legend>Adresse de Livraison</legend>
                        <?php foreach($addresses as $address): ?>
                    <div class="addresses-content">
                        <label for="delivery_<?= $address->id_address ?>">
                            <?=$address->lastname . ' ' . $address->firstname ?> <br>
                            <?= $address->street_number . ' ' . $address->street_name ?> <br>
                            <?= $address->zipcode . ' ' . $address->city?>
                        </label>
                        <input type="radio" id="delivery_<?= $address->id_address ?>" name="delivery" value="<?= $address->id_address ?>" required>
                    </div>
                    <?php endforeach ?>
            </fieldset>
            <fieldset>
                <legend>Adresse de Facturation</legend>
                    <?php foreach($addresses as $address): ?>
                <div class="addresses-content">
                    <label for="invoice_<?= $address->id_address ?>">
                        <?=$address->lastname . ' ' . $address->firstname ?> <br>
                        <?= $address->street_number . ' ' . $address->street_name ?> <br>
                        <?= $address->zipcode . ' ' . $address->city?>
                    </label>
                    <input type="radio" id="invoice_<?= $address->id_address ?>" name="invoice" value="<?= $address->id_address ?>" required>
                </div>
                <?php endforeach ?>
            </fieldset>
        </div>
        <div class="submit-block">
            <button type="submit" class="large-btn primary-btn">
                VALIDER
            </button>
        </div>
    </form>
</div>