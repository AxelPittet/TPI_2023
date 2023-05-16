<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 09.05.2023
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet"
          href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <title>Loc'Habitat</title>
</head>

<body class="bg-gradient-to-r from-blue-950 via-blue-900 to-blue-950">

<!-- main-menu Start -->
<header>
    <div class="navbar bg-base-100">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h8m-8 6h16"/>
                    </svg>
                </label>
                <ul tabindex="0"
                    class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-32">
                    <li><a href="index.php?action=locations">Locations</a></li>
                    <?php if (isset($_SESSION['userEmailAddress'])) : ?>
                        <li tabindex="0">
                            <a href="index.php?action=userLocations" class="justify-between">
                                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="view/img/defaultPFP.png"/>
                                    </div>
                                </label>
                                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24">
                                    <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>
                                </svg>
                            </a>
                            <ul class="p-2 bg-base-100">
                                <li><a href="index.php?action=userLocations">Mes biens</a></li>
                                <li><a href="index.php?action=logout" class="bg-red-800">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="index.php?action=login">S'authentifier</a></li>
                        <li><a href="index.php?action=register">Créer un compte</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <a href="index.php?action=home" class="btn btn-ghost normal-case text-xl">Loc'Habitat</a>
        </div>
        <div class="navbar-center">
            <div class="form-control">
                <input id="inputSearch" type="text" placeholder="Rechercher" class="input input-bordered"/>
            </div>
        </div>
        <div class="navbar-end hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="index.php?action=locations">Locations</a></li>
                <?php if (isset($_SESSION['userEmailAddress'])) : ?>
                    <li tabindex="0">
                        <a href="index.php?action=userLocations">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                 viewBox="0 0 24 24">
                                <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"/>
                            </svg>
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full">
                                    <img src="view/img/defaultPFP.png"/>
                                </div>
                            </label>
                        </a>
                        <ul class="p-2 bg-base-100">
                            <li><a href="index.php?action=userLocations">Mes biens</a></li>
                            <li><a href="index.php?action=logout" class="bg-red-800">Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="index.php?action=login">S'authentifier</a></li>
                    <li><a href="index.php?action=register">Créer un compte</a></li>
                <?php endif; ?>
            </ul>
            <br>
        </div>
    </div>
</header>
<!-- main-menu End -->

<div class="content text-neutral-50">
    <?= $content; ?>
</div>

<!-- footer-copyright start -->
<footer class="bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="index.php?action=home"
                                                                                    class="hover:underline">Loc'Habitat™</a>. All Rights Reserved.
    </span>
</footer>
<!-- footer-copyright end -->
<script>
    var input = document.getElementById("inputSearch");
    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            window.location.href = "index.php?action=search&search=" + input.value;
        }
    });
</script>
</body>
</html>