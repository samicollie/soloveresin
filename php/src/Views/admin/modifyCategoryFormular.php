<div class="white-container">
    <h2>Modifier une catégorie</h2>
    <div class="underline">
        <hr>
    </div>
    <form class="category-formular" action="/admin/category/modify" method="post">
        <?php if(isset($errorMessage)): ?>
        <div class="error-message">
            <?= $errorMessage ?>
        </div>
        <?php endif ?>
        <input type="hidden" name="id_category" value="<?= $category->id_category ?>">
        <label for="name">Nom</label>
        <input class="category-input" type="text" id="name" name="name" value="<?= $category->name ?>">
        <div class="formular-action">
            <button class="primary-btn modify-btn" type="submit">MODIFIER</button>
            <a href="/admin/categories/list">
                <button class="primary-btn delete-btn" type="button">ANNULER</button>
            </a>
        </div>
    </form>
</div>