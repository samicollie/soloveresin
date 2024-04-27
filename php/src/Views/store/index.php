<div class="search-block">
    <input id="product-input-search" class="search-bar" type="text" name="search" placeholder="ma recherche...">
    <button id="product-search-btn" class="search-btn" type="submit" name="valid-search">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</div>

<h2>Notre Boutique </h2>
<div class="underline">
    <hr>
</div>
<div class="products-container" id="content-to-replaced">
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
                <p class="product-price"><?= number_format($product->product_price, 2, ',', '') ?> €</p>
                <form action="/cart/add" method="post">
                    <input type="hidden" name="product_id" class="product-id" value="<?= $product->id_product ?>" >
                    <button type="submit" class="add-btn primary-btn add-cart-btn">AJOUTER</button>
                </form>
            </div>
            <div id="error-quantity-<?= $product->id_product ?>" class="error-message none"></div>
            
        </figcaption>
    </section>
    <?php endforeach ?>
</div>

