<div class="products-container">
    <section class="product-block">
        <?php if($product->picture_filename): ?>
            <figure class="product-pic">
                <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
            </figure>
        <?php else: ?>
            <figure class="product-pic" >
                <img src="/assets/images/noimage.png" alt="no image">
            </figure>
        <?php endif ?>
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
            <p class="notice"> <?= $product->count_rating ? " ( " . $product->count_rating ." avis )" : " (aucun avis)" ?></p>
            </div>
            
            <h3 class="product-name"><?= $product->product_name ?></h3>
            <p class="product-description"><?= $product->product_description ?></p>
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
    <div class="comment-container">
        <?php if(isset($comments[0])): ?>
            <h3 class="comment-container-title">Avis</h3>
        <?php foreach($comments as $comment): ?>
            <section class="comment-block">
                <p><?= $comment->user_firstname . ' ' . $comment->user_lastname ?></p>
                <div class="rating-block">
                    <ul class="stars-rating" data-rating="<?= $comment->comment_rating ?>">
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
                </div>
                <div class="underline-center">
                    <hr>
                </div>
                <h4 class="comment-title"><?= $comment->comment_title ?></h4>
                <p class="comment-content"><?= $comment->comment_content ?></p>
            </section>
        <?php endforeach ?>
        <?php endif ?>
    </div>
</div>