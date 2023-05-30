<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 30.05.2023
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
                <div class="carousel max-h-96">
                    <?php
                    $count = 1;
                    foreach ($locations as $location) :
                        $images = explode(',', $location['imageNames']);
                        if ($count < 5) :
                            ?>
                            <div id="slide<?= $count ?>" class="carousel-item relative w-full">
                                <img src="<?= htmlentities($images[0]) ?>" class="object-contain w-full"/>
                                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                    <a href="#slide<?= $count - 1 ?>" class="btn btn-circle">❮</a>
                                    <a href="#slide<?= $count + 1 ?>" class="btn btn-circle">❯</a>
                                </div>
                                <a class="absolute flex justify-between transform -translate-x-1/2 bottom-1 left-1/2"
                                   href="index.php?action=showLocation&locationNumber=<?= htmlentities($location['locationNumber']) ?>">
                                    <button class="btn btn-primary">Accéder</button>
                                </a>
                            </div>
                            <?php
                            $count += 1;
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>