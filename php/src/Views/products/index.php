<div class="search-block">
    <input class="search-bar" type="text" name="search" placeholder="ma recherche...">
    <button class="search-btn" type="submit" name="valid-search"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>

<h2>Notre Boutique </h2>
<div class="underline">
    <hr>
</div>
<div class="products-block">
    <?php foreach($products as $product): ?>
    <section>
        <h3><?= $product->name ?></h3>
        <p><?= $product->description ?></p>
    </section>
    <?php endforeach ?>
</div>