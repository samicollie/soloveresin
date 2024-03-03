<?php if(!$products): ?>
    <div class="white-container">
        Aucun article ne correspond à la recherche.
    </div>
<?php endif ?>
<?php foreach($products as $key => $product): ?>
    <section class="product-block">
        <a href="store/product/<?= $product->id_product ?>">
            <?php if($product->picture_filename): ?>
            <figure class="product-pic">
                <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
            </figure>
            <?php else: ?>
            <figure class="product-pic">
                <img src="/assets/images/noimage.png" alt="no image">
            </figure>
            <?php endif ?>
        </a>
        <figcaption>
            <div class="rating-container">
            <ul class="stars-rating" data-rating="<?= $product->average_rating ?>">
                <li class="star">
                    <div class="star-inner"></div>
                </li>
                <li class="star">
                    <div class="star-inner"></div>
                </li>
                <li class="star">
                    <div class="star-inner"></div>
                </li>
                <li class="star">
                    <div class="star-inner"></div>
                </li>
                <li class="star">
                    <div class="star-inner"></div>
                </li>   
            </ul>
            <p class="notice"> <?= $product->average_rating ? " ( " . $product->rating_count ." avis )" : " (aucun avis)" ?></p>
            </div>
            
            <a href="store/product/<?= $product->id_product ?>"><h3 class="product-name"><?= $product->product_name ?></h3></a>
            <div class="action-block">
                <p class="product-price"><?= $product->product_price ?> €</p>
                <form action="/cart/add" method="post">
                    <input type="hidden" name="product_id" value="<?= $product->id_product ?>" >
                    <input type="hidden" name="current_url" value="<?= $_SERVER['REQUEST_URI'] ?>" >
                    <button type="submit" class="add-btn primary-btn">AJOUTER</button>
                </form>
            </div>
            
        </figcaption>
    </section>
    <?php endforeach ?>