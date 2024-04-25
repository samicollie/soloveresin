<div class="white-container">
    <h2>Politique de confidentialité</h2>
    <div class="underline">
        <hr>
    </div>
    <form id="privacy-policy-formular" class="legal-notices-form" action="/admin/legalNotices/privacypolicy" method="POST">
        <div class="error-message none" id="error-blank"></div>
        <input type="hidden" name="policy-id" value="1">
        <div class="article">
            <label for="collecion">1. Données collectées :</label>
            <textarea class="article-content" name="collection" id="collection"><?= $privacyPolicy->collection?></textarea>
        </div>
        <div class="article">
            <label for="usage">2. Utilisation des données : </label>
            <textarea class="article-content" name="usage" id="usage"><?= $privacyPolicy->usage_data ?></textarea>
        </div>
        <div class="article">
            <label for="conservation">3. Conservation des données : </label>
            <textarea class="article-content" name="conservation" id="conservation"><?= $privacyPolicy->conservation ?></textarea>
        </div>
        <div class="article">
            <label for="security">4. Sécurité des données : </label>
            <textarea class="article-content" name="security" id="security"><?= $privacyPolicy->security_data ?></textarea>
        </div>
        <div class="article">
            <label for="cookie">5. Cookie : </label>
            <textarea class="article-content" name="cookie" id="cookie"><?= $privacyPolicy->cookie ?></textarea>
        </div>
        <div class="article">
            <label for="users-rights">6. Droits des utilisateurs :</label>
            <textarea class="article-content" name="users-rights" id="users-rights"><?= $privacyPolicy->users_rights ?></textarea>
        </div>
        <div class="article">
            <label for="disputes">7. Litiges : </label>
            <textarea class="article-content" name="disputes" id="disputes"><?= $privacyPolicy->disputes ?></textarea>
        </div>
        <div class="article">
            <label for="contact">8. Contact : </label>
            <textarea class="article-content" name="contact" id="contact"><?= $privacyPolicy->contact ?></textarea>
        </div>
        <div class="submit-block">
                <button class="primary-btn large-btn" type="submit">VALIDER</button>
                <a href="/admin/legalNotices"><button class="primary-btn delete-btn" type="button">ANNULER</button></a>
        </div>
    </form>
</div>