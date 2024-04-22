<div class="white-container">
    <h2>Utilisation de Cookie</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="usage-cookie-formular" class="legal-notices-form" action="/admin/legalNotices/usagecookie" method="POST">
        <input type="hidden" name="usage-id" value="1">
        <div class="error-message none" id="error-blank"></div>
        <div class="article">
            <label for="content">Contenu des usages : </label>
            <textarea class="article-content usage-cookie-textarea" name="content" id="content"><?= $usageCookie->content ?></textarea>
        </div>
        <div class="submit-block">
                <button class="primary-btn large-btn" type="submit">VALIDER</button>
                <a href="/admin/legalNotices"><button class="primary-btn delete-btn" type="button">ANNULER</button></a>
        </div>
    </form>
</div>