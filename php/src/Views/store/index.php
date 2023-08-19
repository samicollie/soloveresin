<div class="search-block">
    <input class="search-bar" type="text" name="search" placeholder="ma recherche...">
    <button class="search-btn" type="submit" name="valid-search"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>

<h2>Notre Boutique </h2>
<div class="underline">
    <hr>
</div>
<div class="products-container">
    <?php foreach($products as $key => $product): ?>
    <section class="product-block">
        <a href="store/product/<?= $product->id_product ?>">
            <figure class="product-pic">
                <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
            </figure>
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
                <a href="#"><button class="add-btn">AJOUTER</button></a>
            </div>
            
        </figcaption>
    </section>
    <?php endforeach ?>
</div>