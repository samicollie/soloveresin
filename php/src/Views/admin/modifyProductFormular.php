<div class="white-container">
    <h2>Modifier un article</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="admin-product-formular" class="product-form modify-product-formular" action="/admin/products/modify" method="post" enctype="multipart/form-data">
        <div class="error-message none" id="error-blank"></div>
        <input type="hidden" name="id_product" value="<?= $product->id_product ?>">
        <div class="product-informations">
            <div class="product-image">
                <?php if($product->picture_filename): ?>
                <figure class="image-block">
                    <img src="/assets/images/<?= $product->picture_filename ?>" alt="<?= $product->product_name ?>">
                    <a class="delete-picture" href="/admin/picture/delete/<?= $product->id_picture ?>/<?= $product->id_product ?>">
                        <button class="primary-btn delete-btn" type="button">
                            <i class="fa-regular fa-circle-xmark"></i>
                        </button>
                    </a>
                </figure>
                <?php else: ?>
                <figure class="image-block">
                    <img src="/assets/images/noimage.png" alt="no image">
                </figure>

                <?php endif ?>
                <input type="file" name="image" accept="images/*">
            </div>
            <div class="error-message none" id="error-image-type"></div>
            <div class="error-message none" id="error-image-size"></div>
            <div class="name-price-block">
                <div class="name-price-content name-content">
                    <label for="name">Nom </label>
                    <input type="text" name="name" id="name" value="<?= $product->product_name ?>">
                    <div class="error-message none" id="error-product-name"></div>
                </div>
                <div class="name-price-content price-content">
                    <label for="price">Prix</label>
                    <input type="text" name="price" id="price" value="<?= $product->product_price ?>">
                    <div class="error-message none" id="error-price"></div>
                </div>
            </div>
            <div class="description-block">
                <label for="description">Description </label>
                <textarea name="description" id="description" cols="30" rows="10"><?= $product->product_description ?></textarea>
            </div>
            <div class="formular-action">
                <button class="primary-btn large-btn" type="submit">MODIFIER</button>
                <a href="/admin/products/list">
                    <button class="primary-btn delete-btn" type="button">ANNULER</button>
                </a>
            </div>
        </div>
    </form>
</div>