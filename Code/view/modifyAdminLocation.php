<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 30.05.2023
 */

ob_start();
?>

<?php foreach ($location as $locationInformations) : ?>
    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-screen-md">
                <h1 class="text-5xl font-bold">Modifier une location</h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="card flex-shrink-0 w-full shadow-2xl bg-base-100">
                    <div class="card-body">
                        <form action="index.php?action=admin&adminFunction=locations&adminLocationsFunction=modify" method="post"
                              enctype="multipart/form-data">
                            <div class="form-control">
                                <input type="text" placeholder="N° de location" class="input input-bordered"
                                       name="inputLocationNumber" value="<?= htmlentities($locationInformations['locationNumber']) ?>"
                                       readonly required/>
                            </div>
                            <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                            <div class="form-control">
                                <input type="text" placeholder="Nom" class="input input-bordered"
                                       name="inputLocationName" value="<?= htmlentities($locationInformations['name']) ?>" required/>
                            </div>
                            <br>
                            <div class="form-control">
                                <input type="text" placeholder="Adresse" class="input input-bordered"
                                       name="inputLocationPlace" value="<?= htmlentities($locationInformations['place']) ?>"
                                       required/>
                            </div>
                            <br>
                            <div class="form-control">
                                <input type="text" placeholder="Description" class="input input-bordered"
                                       name="inputLocationDescription"
                                       value="<?= htmlentities($locationInformations['description']) ?>" required/>
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
                                           value="Maison" <?php if (htmlentities($locationInformations['housingType']) == 'Maison') : ?> checked <?php endif; ?>
                                           required/>
                                    <div class="divider-horizontal"></div>
                                    <label for="appartement">
                                        <span class="label-text mr-2">Appartement :</span>
                                    </label>
                                    <input id="appartement" type="radio" name="inputLocationHousingType" class="radio"
                                           value="Appartement" <?php if (htmlentities($locationInformations['housingType']) == 'Appartement') : ?> checked <?php endif; ?>
                                           required/>
                                </div>
                            </div>
                            <br>
                            <div class="form-control">
                                <input type="number" placeholder="Nombre maximum de clients"
                                       class="input input-bordered"
                                       name="inputLocationClientsNb"
                                       value="<?= htmlentities($locationInformations['maximumNbOfClients']) ?>" required/>
                            </div>
                            <br>
                            <div class="form-control">
                                <input type="number" placeholder="Prix par nuit" class="input input-bordered"
                                       name="inputLocationPrice" step=".01"
                                       value="<?= htmlentities($locationInformations['pricePerNight']) ?>" required/>
                            </div>
                            <?php
                            foreach ($locationImages as $locationImage) :
                                $imageName = explode('\\', $locationImage['name'])
                                ?><br>
                                <input class="input input-bordered" type="text"
                                       name="inputLocationExistingImage[]" value="<?= htmlentities($imageName[0]) ?>"
                                       accept="image/*" readonly required>
                                <input type="hidden" name="inputLocationRemovedImages[]" value="">
                                <button type="button" class="btn" onclick="
                                var existingImageValue = $(this).prev().prev().val();
                                $(this).prev().val(existingImageValue);
                                        $(this).prev().prev().prev().remove();
                                        $(this).prev().prev().remove();
                                        $(this).next().remove();
                                        $(this).remove();
                                        ">Supprimer
                                </button><br>
                            <?php endforeach; ?>
                            <br>
                            <button type="button" id="addFile" onclick="addInput()">Ajouter un fichier</button>
                            <div class="form-control mt-6">
                                <input type="submit" value="Modifier la location" class="btn btn-primary"/>
                            </div>
                        </form>
                        <label for="modalFiltering" class="btn">Supprimer la location</label>
                        <input type="checkbox" id="modalFiltering" class="modal-toggle"/>
                        <div class="modal">
                            <div class="modal-box">
                                <label for="modalFiltering"
                                       class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                                <h3>Êtes vous sur de vouloir supprimer cette location ?</h3>
                                <br>
                                <a href="index.php?action=admin&adminFunction=locations&adminLocationsFunction=delete&locationNumber=<?= htmlentities($locationInformations['locationNumber']) ?>" type="button"
                                   class="btn btn-primary">
                                    <button>Oui</button>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

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
        <?php if (isset($modifyLocationErrorMessage)) :?>
        alert("<?= $modifyLocationErrorMessage ?>");
        <?php endif;?>
    }
</script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>
