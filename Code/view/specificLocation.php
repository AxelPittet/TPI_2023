<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 09.05.2023
 */

ob_start();
?>

<?php foreach ($location as $item) :
    $images = explode(',', $item['imageNames']);
    $startDates = explode(',', $item['startDates']);
    $endDates = explode(',', $item['endDates']);
    ?>
    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-screen">
                <h1 class="text-5xl font-bold"><?= $item['name'] ?></h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <h2 class="text-xl"><?= $item['place'] ?></h2>
                <div class="divider-vertical"></div>

                <div class="flex w-full md:items-center">
                    <?php if ($images[1] != '') :
                        ?>
                        <div class="carousel h-1/2 w-1/2">
                            <?php
                            $imageCount = 0;
                            foreach ($images as $image) :
                                $imageCount += 1;
                                ?>
                                <div id="slide<?= $imageCount ?>" class="carousel-item relative w-full">
                                    <img src="view/img/<?= $image ?>" class="w-full rounded-box"/>
                                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                        <a href="#slide<?= $imageCount - 1 ?>" class="btn btn-circle">❮</a>
                                        <a href="#slide<?= $imageCount + 1 ?>" class="btn btn-circle">❯</a>
                                    </div>
                                </div>
                            <?php endforeach;
                            ?>
                        </div>
                    <?php else : ?>
                        <img src="view/img/<?= $images[0] ?>" class="h-1/2 w-1/2 rounded-box"/>
                    <?php endif; ?>
                    <div class="divider-horizontal"></div>
                    <div>
                        <p class="py-6 text-justify"><?= $item['description'] ?></p>
                        <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                        <h2 class="text-xl text-left">Spécifications</h2>
                        <p class="py-6 text-left">Prix pour une nuit : <?= $item['pricePerNight'] ?> CHF<br>
                            Nombre maximal de clients : <?= $item['maximumNbOfClients'] ?><br>
                            Calendrier des disponibilités :
                            <input id="datepickerInformation" class="bg-transparent w-5" type="image"
                                   src="view/img/calendar.png">
                        </p>
                    </div>
                </div>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <?php
                if (isset($_SESSION['userEmailAddress'])) :
                    ?>
                    <a href="index.php?action=reserv">
                        <button class="btn btn-primary">Réservez dès maintenant !</button>
                    </a>
                <?php
                else:
                    ?>
                    <a href="index.php?action=register">
                        <button class="btn btn-primary">Créez un compte pour réserver !</button>
                    </a>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            var date_range = [ <?php
                $dateCount = 0;
                foreach ($startDates as $startDate):
                ?>
                ["<?= $startDate ?>", "<?= $endDates[$dateCount] ?>"],
                <?php
                $dateCount += 1;
                endforeach; ?>
            ];

            $("#datepickerInformation").datepicker({
                beforeShowDay: function (date) {

                    var string = $.datepicker.formatDate('mm-dd-yy', date);

                    for (var i = 0; i < date_range.length; i++) {

                        if (Array.isArray(date_range[i])) {

                            var from = new Date(date_range[i][0]);
                            var to = new Date(date_range[i][1]);
                            var current = new Date(string);

                            if (current >= from && current <= to) return false;
                        }

                    }
                    return [date_range.indexOf(string) == -1]
                }
            });
        });
    </script>

<?php
endforeach;
$content = ob_get_clean();
require "gabarit.php";
?>