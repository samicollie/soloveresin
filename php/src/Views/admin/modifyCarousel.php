<div class="white-container">
    <h2>Modifier la page d'accueil</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="admin-announces-formular" action="/admin/announces/modify" method="POST">
        <input type="hidden" name="products-announced" value="<?= $productsAnnounced ?>">
        <div class="input-block">
            <label for="title">Titre de l'annonce</label>
            <input type="text" id="title" name="title" value="<?= $carouselTitle ?>">
        </div>
        <div class="product-checkbox-container">
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
                    <input type="checkbox" name="<?= $product->id_product ?>" <?php if($product->announced) echo 'checked' ?> >
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <div class="submit-block">
            <button class="primary-btn large-btn" type="submit">MODIFIER</button>
            <a href="/admin/dashboard">
                <button class="primary-btn delete-btn" type="button">ANNULER</button>
            </a>
        </div>
    </form>
</div>