<div class="white-container">
    <h2>Conditions générales de vente</h2>
    <div class="underline">
        <hr>
    </div>
    <div class="updated">Mises à jour le <?= $updatedDate ?></div>
    <section>
        <h3>1. Identification du vendeur : </h3>
        <p><?=nl2br($termsOfSale->sailor_id) ?></p>
    </section>
    <section>
        <h3>2. Produits et Prix : </h3>
        <p><?=nl2br($termsOfSale->products_and_prices) ?></p>
    </section>
    <section>
        <h3>3. Processus de commande : </h3>
        <p><?=nl2br($termsOfSale->command_process) ?></p>
    </section>
    <section>
        <h3>4. Paiement : </h3>
        <p><?=nl2br($termsOfSale->payement) ?></p>
    </section>
    <section>
        <h3>5. Livraison : </h3>
        <p><?=nl2br($termsOfSale->delivery) ?></p>
    </section>
    <section>
        <h3>6. Droit de rétractation : </h3>
        <p><?=nl2br($termsOfSale->right_of_cancellation) ?></p>
    </section>
    <section>
        <h3>7. Litiges : </h3>
        <p><?=nl2br($termsOfSale->disputes) ?></p>
    </section>
    <section>
        <h3>8. Modification des conditions générales de vente : </h3>
        <p><?=nl2br($termsOfSale->modification) ?></p>
    </section>
</div>