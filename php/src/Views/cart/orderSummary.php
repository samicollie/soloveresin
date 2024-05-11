<div class="white-container">
    <h2>Récapitulatif de commande</h2>
    <div class="underline">
        <hr>
    </div>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Qté</th>
                <th>Prix</th>
            </tr>
        </thead>
        <?php $price = 5.30; ?>
        <tbody>
        <?php foreach($cart as $product): ?>
            <tr>
                <td>
                    <figure>
                        <img class="thumbnail" src="/assets/images/<?= $product[0]->picture_filename ?>" alt="<?= $product[0]->product_name ?>">
                    </figure>
                    <figcaption>
                        <?= $product[0]->product_name ?>
                    </figcaption>
                </td>
                <td>
                    <?= $product[1] ?>
                </td>
                <td>
                    <?php echo number_format($product[0]->product_price *$product[1], 2, ',', '') . "€"; ?>
                </td>
            </tr>
        <?php $price += $product[0]->product_price * $product[1]; ?>
        <?php endforeach ?>
        
            <tr>
                <td colspan="2">Frais de livraison</td>
                <td> 5,30€</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"> SOUS TOTAL</td>
                <td><?= number_format($price, 2, ',', '') ?></td>
            </tr>
        </tfoot>
    </table>
    <section>
        <h3>Adresse de livraison : </h3>
        <p> <?= $addresses['delivery']->firstname . ' ' . $addresses['delivery']->lastname  ?></p>
        <p><?= $addresses['delivery']->street_number . ' ' . $addresses['delivery']->street_name ?></p>
        <p><?= $addresses['delivery']->zipcode . ' ' . $addresses['delivery']->city ?></p>
    </section>
    <section>
        <h3>Adresse de facturation : </h3>
        <p> <?= $addresses['invoice']->firstname . ' ' . $addresses['invoice']->lastname  ?></p>
        <p><?= $addresses['invoice']->street_number . ' ' . $addresses['invoice']->street_name ?></p>
        <p><?= $addresses['invoice']->zipcode . ' ' . $addresses['invoice']->city ?></p>
    </section>
    <form id="order-summary-formular" action="/cart/order/summary" method="POST">
        <input type="hidden" name="token_csrf" value="<?= $tokenCSRF ?>">
        <input type="hidden" name="price" value="<?= $price ?>">
        <div class="submit-block">
            <button class="primary-btn high-btn" type="submit">PASSER AU PAIEMENT</button>
        </div>
    </form>
</div>