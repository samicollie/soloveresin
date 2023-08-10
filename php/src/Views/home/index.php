<h1>Tous nos produits </h1>

<?php foreach($products as $product): ?>
    <section>
        <h2><?= $product->name ?></h2>
        <p><?= $product->description ?></p>

    </section>
<?php endforeach ?>