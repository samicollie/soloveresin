<div class="products-container">
    <section class="product-block">
            <figure class="product-pic">
                <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
            </figure>
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
            <p></p>
            <div class="action-block">
                <p class="product-price"><?= $product->product_price ?> €</p>
                <a href="#"><button class="add-btn">AJOUTER</button></a>
            </div>
            
        </figcaption>
    </section>
    <div class="comment-container">
        <?php if(isset($comments)): ?>
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