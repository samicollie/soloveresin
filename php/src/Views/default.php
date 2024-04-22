<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coda&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" >
    <script src="/assets/js/index.js" type="module"></script>   
    <title><?= $title ?></title>
</head>
<body>
    <header class="main-header">
        <a href="/"><img class="logo" src="/assets/images/logo.png" alt="logo"></a>
        <h1 class="slogan">So Love Resin</h1>
    </header>

    <main class="container">

        <?= $content ?>
    </main>
    <nav class="hamburger-menu">
        <ul>
            <li>
                <a class="link-hamburger-menu" href="#">
                    <button class="primary-btn high-btn big-btn">
                        CONDITIONS GENERALES DE VENTE (CGV)
                    </button>
                </a>
            </li>
            <li>
                <a class="link-hamburger-menu" href="#">
                    <button class="primary-btn high-btn big-btn">
                        POLITIQUE DE CONFIDENTIALITE
                    </button>
                </a>
            </li>
            <li>
                <a class="link-hamburger-menu" href="#">
                    <button class="primary-btn high-btn big-btn">
                        USAGE DE COOKIE
                    </button>
                </a>
            </li>
            <li>
                <a class="link-hamburger-menu" href="#">
                    <button class="primary-btn high-btn big-btn">
                        NOUS CONTACTER
                    </button>
                </a>
            </li>    
        </ul>
    </nav>
    <nav class="nav-mobile">
        <a href="/store">
            <span id="link-store" class="<?= preg_match("/^\/store/", $_SERVER["REQUEST_URI"]) ? 'selected-tab' : '' ?>">
                <i class="fa-solid fa-store"></i>
            </span>
        </a>
        <a href="
        <?php 
        if($this->isLoggedIn){
                echo '/profile';
            }else{
                echo '/login';
            } ?>
        ">
            <span id="link-auth" class="<?php if(preg_match("/^\/profile/", $_SERVER["REQUEST_URI"]) ||
                                preg_match("/^\/login/", $_SERVER["REQUEST_URI"]) ||
                                preg_match("/^\/register/", $_SERVER["REQUEST_URI"])){
                                    echo 'selected-tab';
                                } ?>" >
                <i class="fa-solid fa-user"></i>
            </span>
        </a>
        <?php if($this->userRole === '["ROLE_ADMIN"]'): ?>
        <a  class="link-nav-mobile" href="/admin/dashboard">
            <span id="link-admin" class="<?= preg_match("/^\/admin/", $_SERVER["REQUEST_URI"]) ? 'selected-tab' : '' ?>">
                    <i class="fa-solid fa-gears"></i>
            </span>
        </a>
        <?php else: ?>
        <a href="/cart" id="cart-link">
            <span id="link-cart" class="<?= preg_match("/^\/cart/", $_SERVER["REQUEST_URI"]) ? 'selected-tab' : '' ?>">
                <span class="cart-tab">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="product-counter"><?=$productCounter ?></span>
                </span>
            </span>
        </a>
        <?php endif ?>
        <span id="tab-hamburger-icon">
            <i class="fa-solid fa-bars"></i>
        </span>

    </nav>
</body>
</html>