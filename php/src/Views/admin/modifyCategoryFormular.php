<div class="white-container">
    <h2>Modifier une catégorie</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="admin-category-formular" class="category-formular modify-category-formular" action="/admin/category/modify" method="post">
        <div class="error-message none" id="error-blank"></div>
        <input type="hidden" name="id_category" value="<?= $category->id_category ?>">
        <label for="name">Nom</label>
        <input class="category-input" type="text" id="name" name="name" value="<?= $category->name ?>">
        <div id="error-category" class="error-message none"></div>
        <div class="formular-action">
            <button class="primary-btn modify-btn" type="submit">MODIFIER</button>
            <a href="/admin/categories/list">
                <button class="primary-btn delete-btn" type="button">ANNULER</button>
            </a>
        </div>
    </form>
</div>