<div class="search-block">
    <input id="product-input-search" class="search-bar" type="text" name="search" placeholder="ma recherche...">
    <button id="product-search-btn" class="search-btn" type="submit" name="valid-search"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>
<?php if(isset($errorMessage)): ?>
    <div class="error-message">
        <?= $errorMessage ?>
    </div>
<?php endif ?>
<?php if(isset($successMessage)): ?>
    <div class="success-message">
        <?= $successMessage ?>
    </div>
<?php endif ?>
<div class="white-container" >
    <h2>Liste des articles</h2>
    <div class="underline">
        <hr>
    </div>
    <div class="modify-products-list" id="content-to-replaced">
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
    </div>
</div>