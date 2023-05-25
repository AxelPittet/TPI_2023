<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 25.05.2023
 */

ob_start();
?>

<div class="hero min-h-screen">
    <div class="hero-content text-center">
        <div class="max-w-screen">
            <label for="modalFiltering" class="btn">Recherche avancée</label>
            <input type="checkbox" id="modalFiltering" class="modal-toggle"/>
            <div class="modal">
                <div class="modal-box">
                    <label for="modalFiltering" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                    <h3 class="font-bold text-2xl">Filtres</h3>
                    <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                    <form action="index.php?action=filters" method="post">

                        <h3 class="text-lg">Lieu</h3><br>
                        <input name="inputPlace" class="input input-bordered" type="text">
                        <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>

                        <h3 class="text-lg">Dates</h3><br>
                        <div class="flex w-full">
                            <p class="label-text">Date de début : <input id="startDatepickerFilter"
                                                                         name="inputStartDate"
                                                                         class="input input-bordered" type="text"
                                                                         autocomplete="off" readonly
                                                                         onchange="minEndDate(this.value)"></p>
                            <p class="label-text">Date de fin : <input id="endDatepickerFilter" name="inputEndDate"
                                                                       class="input input-bordered" type="text"
                                                                       autocomplete="off" readonly></p>
                        </div>
                        <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>

                        <h3 class="text-lg">Nombres de personnes</h3>
                        <input id="clientsRange" name="inputClientsRange" type="range" min="1" max="20" value="2"
                               class="range" step="1"
                               oninput="printClientsRange(this.value)"/>
                        <div class="w-full flex justify-between text-xs px-2">
                            <span class="label-text">1</span>
                            <span class="label-text">5</span>
                            <span class="label-text">10</span>
                            <span class="label-text">15</span>
                            <span class="label-text">20</span>
                        </div>
                        <br>
                        <p class="label-text">Valeur :
                            <span id="clientsRangeValue">2</span></p>
                        <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>

                        <h3 class="text-lg">Type de location</h3>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Maison</span>
                                <input name="inputCheckboxHouse" type="checkbox" class="checkbox"/>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Appartement</span>
                                <input name="inputCheckboxApartment" type="checkbox" class="checkbox"/>
                            </label>
                        </div>

                        <div class="modal-action">
                            <input type="submit" value="Valider" class="btn"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="divider-vertical"></div>
            <h1 class="text-5xl font-bold">Locations</h1>
            <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
            <?php
            $count = 0;
            foreach ($locations

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
                    <div class="card card-compact bg-base-100 shadow-xl max-h-80 min-h-full">
                        <figure><img src="<?= $images[0] ?>" alt="<?= $location['name'] ?>" class="w-96"/>
                        </figure>
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

<script>
    function printClientsRange(rangeValue) {
        document.getElementById("clientsRangeValue").innerHTML = rangeValue;
    }

    function minEndDate(startDateValue) {
        var startDate = new Date(startDateValue);
        var minEndDate = new Date(startDate.getTime() + (24 * 60 * 60 * 1000));

        $("#endDatepickerFilter").datepicker("option", "minDate", minEndDate);
    }

    $("#startDatepickerFilter").datepicker({
        minDate: 0
    });

    $("#endDatepickerFilter").datepicker();
</script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>
