<div class="white-container">
    <h2>Modifier une catégorie :</h2>
    <div class="underline">
        <hr>
    </div>
    <div class="list-categories">
        <?php if(isset($successMessage)): ?>
        <div class="success-message">
            <?= $successMessage ?>
        </div>
        <?php endif ?>
        <?php foreach($categories as $category): ?>
            <div class="row-category">
                <p><?= $category->name ?></p>
                <div class="action-modify-list">
                <a href="/admin/category/modify/<?= $category->id_category ?>">
                    <button class="primary-btn modify-btn">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </a>
                <form action="/admin/category/delete" method="post">
                    <input type="hidden" name="id_category" value="<?= $category->id_category ?>">
                    <button class="delete-btn primary-btn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
            </div>
        <?php endforeach ?>
    </div>
</div>