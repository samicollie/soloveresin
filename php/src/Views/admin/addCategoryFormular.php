<div class="white-container">
    <h2>Ajouter une catégorie</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="admin-category-formular" class="category-formular add-category-formular" action="/admin/category/add" method="post">
        <div class="error-message none" id="error-blank"></div>
        <input type="hidden" name="id_category" >
        <label for="name">Nom</label>
        <input class="category-input" type="text" id="name" name="category" >
        <div id="error-category" class="error-message none"></div>
        <div class="formular-action">
            <button class="primary-btn large-btn" type="submit">AJOUTER</button>
            <a href="/admin/dashboard">
                <button class="primary-btn delete-btn" type="button">ANNULER</button>
            </a>
        </div>
    </form>
</div>