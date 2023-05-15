<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 15.05.2023
 */

ob_start();
?>

    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="w-screen">
                <h1 class="text-5xl font-bold">Modifier une location</h1>
                <div class="divider"></div>
                <div class="card-body">
                    <form action="index.php?action=userLocations&userLocationsFunction=modify" method="post">
                        <div class="form-control">
                            <label class="label" for="locationNumber">
                                <span class="label-text">N° de location</span>
                            </label>
                            <input type="text" placeholder="location number" class="input input-bordered"
                                   name="inputLocationNumber" list="locationNumber" required/>
                            <datalist id="locationNumber">
                                <?php
                                foreach ($userLocations

                                as $userLocation):
                                ?>
                                <option value="<?= $userLocation['locationNumber'] ?>">
                                    <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="form-control mt-6">
                            <input type="submit" value="Modifier la location" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>