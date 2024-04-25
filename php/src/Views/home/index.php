<div class="white-container">
    <h2 class="landing-page-title"><?= $carouselTitle ?></h2>

    <?php if(isset($announcedProducts) && !empty($announcedProducts)): ?>
        <div class="carousel-container">
            <?php foreach($announcedProducts as $product): ?>
                <div class="carousel-slide">
                    <a href="store/product/<?= $product->id_product ?>">
                        <figure class="product-pic">
                            <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
                        </figure>
                    </a>
                </div>
                <?php endforeach ?>
        </div>
            <?php endif ?>
        <div class="link-container">
            <a href="/store">
                <button class="store-link">ACCEDER A NOTRE BOUTIQUE</button>
            </a>
        </div>
</div>

