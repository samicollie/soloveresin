<div class="cart-container">

    <h2>Mon panier</h2>
    <div class="underline">
        <hr>
    </div>
    
    <?php $price = 0; ?>
    <?php if(!empty($cart)): ?>

        <?php foreach($cart as $product): ?>
            <?php $price += $product[0]->product_price * $product[1] ?>
            <section class="cart-product">
                <div class="product-cart-content">
                    <figure>
                        <img src="/assets/images/<?= $product[0]->picture_filename ?>" alt="<?= $product[0]->product_name ?>">
                    </figure>
                    <figcaption>
                        <h3><?= $product[0]->product_name ?></h3>
                        <div class="product-cart-price">
                            <?= $product[0]->product_price ?> €
                        </div>
                        <div class="quantity_block">
                            <form action="/cart/delete" method="post">
                                <input type="hidden" name="product_id" value="<?=$product[0]->id_product ?>">
                                <button type="submit" class="delete-product-cart"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            <form class="quantity_product" action="/cart/updateQuantity" method="post">
                                <label for="quantity_product"> Quantité </label>
                                <select class="quantity_product" name="quantity" id="quantity_product">
                                    <?php for($i = 0; $i<= $product[1]; $i++): ?>
                                        <?php if($i === $product[1]): ?>
                                            <option selected=true value="<?= $i ?>"><?= $i ?></option>
                                        <?php else: ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endif ?>
                                    <?php endfor ?>
                                </select>
                            </form>
                        </div>
                
                    </figcaption>
                </div>
            </section>
        <?php endforeach ?>
    <?php else: ?>
        <div class="no-cart-product">
            Aucun article dans votre panier
        </div>
    <?php endif ?>
    <div class="underline-center">
        <hr>
    </div>
    <div class="total-price">
        Sous-Total : <?= $price ?> €
    </div>
</div>