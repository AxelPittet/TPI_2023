<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 12.05.2023
 */

ob_start();
?>
<div class="hero min-h-screen">
    <div class="hero-content text-center">
        <div class="w-screen">
            <h1 class="text-5xl font-bold">Mes biens</h1>
            <div class="divider-vertical"></div>
            <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
            <div class="divider-vertical"></div>
            <a href="index.php?action=userLocations&userLocationsFunction=add">
                <button class="btn btn-primary">Ajouter une location</button>
            </a>
            <div class="divider-vertical"></div>
            <a href="index.php?action=userLocations&userLocationsFunction=modify">
                <button class="btn btn-primary">Modifier une location</button>
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>
