<div class="ads-news">
    <h2 class="new-title"><span class="anime-title">Nouvelle collection</span></h2>
    <div class="ads-new new-1 circle"><img class="ads-pic" src="/images/boucleoreille.jpg" alt="boucle d'oreille"></div>
    <div class="ads-new new-2 circle"><img class="ads-pic" src="/images/marquepage.jpg" alt="marque page"></div>
    <div class="ads-new new-3 circle"><img class="ads-pic" src="/images/dessousverre.jpg" alt="dessous de verre"></div>
    <a href="#"><button class="ads-btn ads-btn-position-1">VOIR</button></a>
</div>
<div class="ads-best-rating">
    <h2 class="ads-rating-title"><span class="anime-title">Coups de coeur</span></h2>
    <div class="ads-rating ads-rating1 circle"><img class="ads-pic" src="/images/porteclepoisson.jpg" alt="boucle d'oreille"></div>
    <div class="ads-rating ads-rating2 circle"><img class="ads-pic" src="/images/dominodore.jpg" alt="marque page"></div>
    <div class="ads-rating ads-rating3 circle"><img class="ads-pic" src="/images/videpoche2.jpg" alt="dessous de verre"></div>
    <a href="#"><button class="ads-btn ads-btn-position-2">VOIR</button></a>
</div>

<div class="access-shop">
    <?php foreach($pictures as $index => $picture): ?>
        <div class="access-shop-pic product-<?= $index ?>">
            <img src="/images/<?= $picture->filename ?>" alt="images objets en résine">
        </div>
    <?php endforeach ?>
    <a href="/boutique"><button class="ads-btn access-shop-btn"> ACCES BOUTIQUE</button></a>
</div>

