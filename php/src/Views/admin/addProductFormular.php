<div class="white-container">
    <h2>Ajouter un article</h2>
    <div class="underline">
        <hr>
    </div>
    <?php if(isset($errorMessage)): ?>
        <div class="error-message">
            <?= $errorMessage ?>
        </div>
    <?php endif ?>
    <form class="product-form" action="/admin/products/add" method="post" enctype="multipart/form-data">
        <div class="product-informations">
            <div class="product-image"> 
                <figure class="image-block">
                    <img src="/assets/images/noimage.png" alt="no image">
                </figure>
                <input type="file" name="image" accept="images/*">
            </div>
            <div class="name-price-block">
                <div class="name-price-content name-content">
                    <label for="name">Nom </label>
                    <input type="text" name="name" id="name">
                </div>
                <div class="name-price-content price-content">
                    <label for="price">Prix</label>
                    <input type="text" name="price" id="price">
                </div>
            </div>
            <div class="description-block">
                <label for="description">Description </label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div class="formular-action">
                <button class="primary-btn modify-btn" type="submit">AJOUTER</button>
                <a href="/admin/dashboard">
                    <button class="primary-btn delete-btn" type="button">ANNULER</button>
                </a>
            </div>
        </div>
    </form>
</div>