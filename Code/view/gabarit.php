<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 08.05.2023
 */

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Axel Pittet">
    <meta name="description" content="This page is the gabarit of the site">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/view/css/output.css" rel="stylesheet">
    <title>Loc'Habitat</title>
</head>

<body>

<!-- main-menu Start -->
<header>
    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <a href="index.php?action=home" class="btn btn-ghost normal-case text-xl">Loc'Habitat</a>
        </div>
        <div class="navbar-center">
            <div class="form-control">
                <input type="text" placeholder="Rechercher" class="input input-bordered" />
            </div>
        </div>
        <div class="navbar-end">
            <ul class="menu menu-horizontal px-1">
                <li><a href="index.php?action=login">Locations</a></li>
                <li><a href="index.php?action=login">S'authentifier</a></li>
                <li><a href="index.php?action=register">Créer un compte</a></li>
            </ul>
        </div>
    </div>
</header><!-- /.top-area-->

<!-- main-menu End -->
<div class="content">
    <?= $content; ?>
</div>

<!-- footer-copyright start -->
<footer class="bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="index.php?action=home"
                                                                                    class="hover:underline">Loc'Habitat™</a>. All Rights Reserved.
    </span>
</footer>
<!-- footer-copyright end -->

</body>
</html>