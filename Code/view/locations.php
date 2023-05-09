<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 09.05.2023
 */

ob_start();
?>

<div class="hero min-h-screen">
    <div class="hero-content text-center">
        <div class="max-w-screen">
            <div class="divider-vertical"></div>
            <h1 class="text-5xl font-bold">Locations</h1>
            <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
            <?php foreach ($locations

                           as $location) :
                $count += 1;
                $images = explode(',', $location['imageNames']);
                if ($count == 1) :
                    ?>
                    <div class="flex">
                <?php
                endif;
                ?>
                <a href="index.php?action=showLocation&locationNumber=<?= $location['locationNumber'] ?>">
                    <div class="card card-compact bg-base-100 shadow-xl">
                        <figure><img src="view/img/<?= $images[0] ?>" alt="<?= $location['name'] ?>" class="w-96"/></figure>
                        <div class="card-body">
                            <h2 class="card-title relative">
                                <?= $location['name'] ?>
                            </h2>
                            <p class="text-left"><?= $location['place'] ?></p>
                            <p class="text-left"><?= $location['pricePerNight'] . ' CHF' ?></p>
                        </div>
                    </div>
                </a>
                <?php
                if ($count == 3) :
                    $count = 0;
                    ?>
                    </div>
                    <div class="divider-vertical"></div>
                <?php
                else :
                    ?>
                    <div class="divider-horizontal"></div>
                <?php endif;
            endforeach;
            if ($count != 0) : ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>
