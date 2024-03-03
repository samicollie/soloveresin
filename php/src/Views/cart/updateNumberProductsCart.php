<span class="<?= preg_match("/^\/cart/", $currentUrl) ? 'selected-tab' : '' ?>">
    <span class="cart-tab">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="product-counter"><?=$productCounter ?></span>
    </span>
</span>