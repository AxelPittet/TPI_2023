<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 23.05.2023
 */

ob_start();
?>

    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="w-screen">
                <h1 class="text-5xl font-bold">Modifier une location</h1>
                <div class="divider"></div>
                <div class="card-body">
                    <form action="index.php?action=admin&adminFunction=locations&adminLocationsFunction=modify" method="post">
                        <div class="form-control">
                            <label class="label" for="locationNumber">
                                <span class="label-text">N° de location</span>
                            </label>
                            <select id="locationNumber" class="input input-bordered" name="inputLocationNumber"
                                    required>
                                <?php
                                foreach ($locations

                                         as $location):
                                    ?>
                                    <option value="<?= $location['locationNumber'] ?>"><?= $location['locationNumber'] ?></option>
                                <?php endforeach; ?>
                            </select>
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