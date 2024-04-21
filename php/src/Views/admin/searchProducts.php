<?php if(!$products): ?>
    
        <p>Aucun article ne correspond à la recherche.</p>
    
<?php endif ?>
<?php foreach($products as $product): ?>
        <div class="row-products">
            <div class="content-products">
                    <?php if($product->picture_filename): ?>
                    <figure>
                        <img class="thumbnail" src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
                    </figure>
                    <?php else: ?>
                    <figure>
                        <img class="thumbnail" src="/assets/images/noimage.png" alt="no image">
                    </figure>
                    <?php endif ?>
                    <figcaption class="name-modify-products">
                        <?= $product->product_name ?>
                    </figcaption>
            </div>
            <div class="action-modify-list">
                <a href="/admin/products/modify/<?= $product->id_product ?>">
                    <button class="primary-btn large-btn">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </a>
                <form action="/admin/product/delete" method="post">
                    <input type="hidden" name="id_product" value="<?= $product->id_product ?>">
                    <button class="delete-btn primary-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach ?>