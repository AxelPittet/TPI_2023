<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 23.05.2023
 */

ob_start();
?>

    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-screen-md">
                <div class="divider-vertical"></div>
                <div class="divider-vertical"></div>
                <div class="divider-vertical"></div>

                <h1 class="text-5xl font-bold">Bienvenue sur Loc'Habitat !</h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <p class="py-6">Vous pourrez ici trouver de quoi loger pendant votre nouveau voyage cet
                    été ou vos vacances à ski !
                    <br>Proposez également vos appartements et/ou maisons afin de les louer à nos utilisateurs !</p>
                <br>
                <a href="index.php?action=locations">
                    <button class="btn btn-primary">Voir les locations disponibles</button>
                </a>
                <div class="divider-vertical"></div>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="divider-vertical"></div>
                <div class="divider-vertical"></div>
                <h1 class="text-5xl font-bold">Locations en vedette</h1>
                <br>
                <div class="carousel w-full">
                    <div id="slide1" class="carousel-item relative w-full">
                        <img src="view/img/chateau.jpg" class="w-full rounded-box"/>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide4" class="btn btn-circle">❮</a>
                            <a href="#slide2" class="btn btn-circle">❯</a>
                        </div>
                    </div>
                    <div id="slide2" class="carousel-item relative w-full">
                        <img src="view/img/chateau.jpg" class="w-full rounded-box"/>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide1" class="btn btn-circle">❮</a>
                            <a href="#slide3" class="btn btn-circle">❯</a>
                        </div>
                    </div>
                    <div id="slide3" class="carousel-item relative w-full">
                        <img src="view/img/chateau.jpg" class="w-full rounded-box"/>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide2" class="btn btn-circle">❮</a>
                            <a href="#slide4" class="btn btn-circle">❯</a>
                        </div>
                    </div>
                    <div id="slide4" class="carousel-item relative w-full">
                        <img src="view/img/chateau.jpg" class="w-full rounded-box"/>
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#slide3" class="btn btn-circle">❮</a>
                            <a href="#slide1" class="btn btn-circle">❯</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
require "gabarit.php";
?>