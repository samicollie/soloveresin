<div class="white-container">
    <h2>Conditions générales de vente</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="terms-of-sale-formular" class="legal-notices-form" action="/admin/legalNotices/termsofsale" method="POST">
        <div class="error-message none" id="error-blank"></div>
        <input type="hidden" name="terms-id" value="1">
        <div class="article">
            <label for="sailor-id">1. Identification du vendeur :</label>
            <textarea class="article-content" name="sailor-id" id="sailor-id"><?= $termsOfSale->sailor_id ?></textarea>
        </div>
        <div class="article">
            <label for="products-and-prices">2. Produits et Prix : </label>
            <textarea class="article-content" name="products-prices" id="products-and-prices"><?= $termsOfSale->products_and_prices ?></textarea>
        </div>
        <div class="article">
            <label for="command-process">3. Processus de commande : </label>
            <textarea class="article-content" name="command-process" id="command-process"><?= $termsOfSale->command_process ?></textarea>
        </div>
        <div class="article">
            <label for="payement">4. Paiement : </label>
            <textarea class="article-content" name="payement" id="payment"><?= $termsOfSale->payement ?></textarea>
        </div>
        <div class="article">
            <label for="delivery">5. Livraison : </label>
            <textarea class="article-content" name="delivery" id="delivery"><?= $termsOfSale->delivery ?></textarea>
        </div>
        <div class="article">
            <label for="right-of-cancellation">6. Droit de rétractation :</label>
            <textarea class="article-content" name="right-of-cancellation" id="right-of-cancellation"><?= $termsOfSale->right_of_cancellation ?></textarea>
        </div>
        <div class="article">
            <label for="disputes">7. Litiges : </label>
            <textarea class="article-content" name="disputes" id="disputes"><?= $termsOfSale->disputes ?></textarea>
        </div>
        <div class="article">
            <label for="modification">8. Modification des CGV : </label>
            <textarea class="article-content" name="modification" id="modification"><?= $termsOfSale->modification ?></textarea>
        </div>
        <div class="submit-block">
                <button class="primary-btn large-btn" type="submit">VALIDER</button>
                <a href="/admin/legalNotices"><button class="primary-btn delete-btn" type="button">ANNULER</button></a>
        </div>
    </form>
</div>
