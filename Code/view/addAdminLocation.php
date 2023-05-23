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
        <div class="max-w-screen-md">
            <h1 class="text-5xl font-bold">Ajouter une location</h1>
            <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
            <div class="card flex-shrink-0 w-full shadow-2xl bg-base-100">
                <div class="card-body">
                    <form action="index.php?action=admin&adminFunction=locations&adminLocationsFunction=add" method="post"
                          enctype="multipart/form-data">
                        <div class="form-control">
                            <input type="text" placeholder="Nom" class="input input-bordered"
                                   name="inputLocationName" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="text" placeholder="Adresse" class="input input-bordered"
                                   name="inputLocationPlace" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="text" placeholder="Description" class="input input-bordered"
                                   name="inputLocationDescription" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Type d'habitation</span>
                            </label>
                            <div class="input input-bordered flex w-full md:items-center">
                                <label for="maison">
                                    <span class="label-text mr-2">Maison :</span>
                                </label>
                                <input id="maison" type="radio" name="inputLocationHousingType" class="radio"
                                       value="Maison" required/>
                                <div class="divider-horizontal"></div>
                                <label for="appartement">
                                    <span class="label-text mr-2">Appartement :</span>
                                </label>
                                <input id="appartement" type="radio" name="inputLocationHousingType" class="radio"
                                       value="Appartement" required/>
                            </div>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="number" placeholder="Nombre maximum de clients" class="input input-bordered"
                                   name="inputLocationClientsNb" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="number" placeholder="Prix par nuit" class="input input-bordered"
                                   name="inputLocationPrice" step=".01" required/>
                        </div>
                        <br>
                        <input class="file-input file-input-bordered w-full max-w-xs" type="file"
                               name="inputLocationImage[]" accept="image/*" required><br>
                        <button type="button" id="addFile" onclick="addInput()">Ajouter un fichier</button>
                        <div class="form-control mt-6">
                            <input type="submit" value="Ajouter la location" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function addInput() {
        var fileInput = $("<input>").attr({
            class: "file-input file-input-bordered w-full max-w-xs",
            type: "file",
            name: "inputLocationImage[]",
            required: true,
            accept: "image/*"
        });

        var deleteButton = $("<button>").attr("type", "button").text("Supprimer").on("click", function () {
            $(this).prev().prev().remove();
            $(this).prev().remove();
            $(this).remove();
        });

        fileInput.insertBefore($("#addFile"));
        deleteButton.insertBefore($("#addFile"));
        deleteButton.addClass("btn");
        $("<br>").insertBefore($("#addFile"));
    }

    window.onload = function showErrorMessage() {
        <?php if (isset($addLocationErrorMessage)) :?>
        alert("<?= $addLocationErrorMessage ?>");
        <?php endif;?>
    }
</script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>
