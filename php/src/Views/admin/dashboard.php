<?php if(isset($successMessage)): ?>
    <div class="success-message">
        <?= $successMessage ?>
    </div>
<?php endif ?>

<div class="white-container">
    <h2>
        Espace administration
    </h2>
    <div class="underline">
        <hr>
    </div>
    <div class="link-admin-container">
        <a href="/admin/products/add">
            <div class="block-link-admin">
                <i class="fa-solid fa-plus"></i>
                <p>Ajouter Article</p>
            </div>
        </a>
        <a href="/admin/products/list">
            <div class="block-link-admin">
                <i class="fa-solid fa-pen-to-square"></i>
                <p>Modifier Articles</p>
            </div>
        </a>
        <a href="/admin/category/add">
            <div class="block-link-admin">
                <i class="fa-solid fa-plus"></i>
                <p>Ajouter Catégorie</p>
            </div>
        </a>
        <a href="/admin/categories/list">
            <div class="block-link-admin">
                <i class="fa-solid fa-pen-to-square"></i>
                <p>Modifier Catégories</p>
            </div>
        </a>
        <a href="/admin/announces/modify">
            <div class="block-link-admin">
                <i class="fa-solid fa-pen-to-square"></i>
                <p>Modifier Annonces</p>
            </div>
        </a>

    </div>
</div>

<div class="white-container">
<h2>
        Espace vente
    </h2>
    <div class="underline">
        <hr>
    </div>
    <div class="link-admin-container">
        <a href="/admin/orders">
            <div class="block-link-admin">
                <i class="fa-solid fa-eye"></i>
                <p>Voir Commandes</p>
            </div>
        </a>
        <a href="/admin/products/list">
            <div class="block-link-admin">
                <i class="fa-solid fa-eye"></i>
                <p>Voir Factures</p>
            </div>
        </a>
    </div>
</div>