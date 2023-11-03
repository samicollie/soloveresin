<div class="white-container">
    <div class="profile-title-block">
        <div class="profile-title">
            <h3>Mon profil </h3>
            <a href="/profile/contact/modify/<?= $user->id_user ?>"><button class="modify-btn primary-btn"><i class="fa-regular fa-pen-to-square"></i></button></a>
        </div>
        <a href="/logout"><button class="delete-btn primary-btn"><i class="fa-solid fa-power-off"></i></button></a>
    </div>
    <div class="underline">
        <hr>
    </div>
    <div class="info-content">
        <span class="identity"><?= $user->firstname ?></span>
        <span class="identity"><?= $user->lastname ?></span>
    </div>
    <div class="contact-details">
        <h4>Coordonnées : </h4>
        <p>
            Email : <?= $user->email ?>
        </p>
        <p class="phone-number">
            Tel : <?= $user->phone_number ? $user->phone_number : " - "; ?>
        </p>
        <?php if($user->role != '["ROLE_ADMIN"]'): ?>
        <div class="addresses">
            <h4>Adresses :</h4>
            <?php if(isset($errorMessage)): ?>
            <div class="error-message">
            <?= $errorMessage ?>
            </div>
            <?php endif ?>
            <?php foreach($addresses as $address): ?>
            <div class="address">
                <div>
                    <p><?= $address->firstname ?> <?= $address->lastname ?></p>
                    <p><?= $address->street_number ?> <?= $address->street_name ?></p>
                    <p><?= $address->zipcode ?> <?= $address->city ?></p>
                </div>
                <div class="address-action">
                    <a href="/profile/address/modify/<?= $address->id_address ?>"><button class="modify-btn primary-btn"><i class="fa-regular fa-pen-to-square"></i></button></a>
                    <form action="/profile/address/delete/" method="post">
                        <input type="hidden" name="id_address" value="<?= $address->id_address ?>">
                        <button type="submit" class="delete-btn primary-btn"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="underline-center">
                <hr>
            </div>
            <?php endforeach; ?>
            <div class="add-address">
                <a href="/profile/address/add"><button class="primary-btn add-address-btn">AJOUTER UNE ADRESSE</button></a>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>