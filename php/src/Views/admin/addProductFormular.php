<div class="white-container">
    <h2>Ajouter un article</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="admin-product-formular" class="product-form add-product-formular" action="/admin/products/add" method="post" enctype="multipart/form-data">
        <div class="error-message none" id="error-blank"></div>
        <div class="product-informations">
            <div class="product-image"> 
                <figure class="image-block">
                    <img src="/assets/images/noimage.png" alt="no image">
                </figure>
                <input type="file" name="image" accept="images/*">
            </div>
            <div class="error-message none" id="error-image-type"></div>
            <div class="error-message none" id="error-image-size"></div>
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
            <div class="error-message none" id="error-product-name"></div>
            <div class="error-message none" id="error-price"></div>
            <div class="description-block">
                <label for="description">Description </label>
                <textarea name="description" id="description"></textarea>
            </div>
            <div class="formular-action">
                <button class="primary-btn large-btn" type="submit">AJOUTER</button>
                <a href="/admin/dashboard">
                    <button class="primary-btn delete-btn" type="button">ANNULER</button>
                </a>
            </div>
        </div>
    </form>
</div>