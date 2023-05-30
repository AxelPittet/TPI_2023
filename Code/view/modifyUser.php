<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 30.05.2023
 */

ob_start();
?>

<?php foreach ($user as $userInformations) :
    ?>
    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="w-screen">
                <h1 class="text-5xl font-bold">Modifier un utilisateur : <?= htmlentities($userInformations['email']) ?></h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="card-body">
                    <form action="index.php?action=admin&adminFunction=users&usersFunction=modify" method="post">
                        <input type="hidden" name="inputUserId" value="<?= htmlentities($userInformations['id']) ?>">
                        <div class="form-control">
                            <label class="label" for="userFirstName">
                                <span class="label-text">Prénom</span>
                            </label>
                            <input type="text" placeholder="Prénom" class="input input-bordered"
                                   name="inputUserFirstName" value="<?= htmlentities($userInformations['firstname']) ?>" required/>
                        </div>
                        <div class="form-control">
                            <label class="label" for="userLastName">
                                <span class="label-text">Nom</span>
                            </label>
                            <input type="text" placeholder="Nom" class="input input-bordered"
                                   name="inputUserLastName" value="<?= htmlentities($userInformations['lastname']) ?>" required/>
                        </div>
                        <div class="form-control">
                            <label class="label" for="userPhoneNumber">
                                <span class="label-text">N° de téléphone</span>
                            </label>
                            <input type="number" placeholder="079 ... .. .." class="input input-bordered"
                                   name="inputUserPhoneNumber" maxlength="15" value="<?= htmlentities($userInformations['phoneNumber']) ?>" required/>
                        </div>
                        <div class="form-control mt-6">
                            <input type="submit" value="Modifier l'utilisateur" class="btn btn-primary"/>
                        </div>
                    </form>
                    <label for="modalFiltering" class="btn">Supprimer l'utilisateur</label>
                    <input type="checkbox" id="modalFiltering" class="modal-toggle"/>
                    <div class="modal">
                        <div class="modal-box">
                            <label for="modalFiltering"
                                   class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                            <h3>Êtes vous sur de vouloir supprimer cet utilisateur ?</h3>
                            <br>
                            <a href="index.php?action=admin&adminFunction=users&usersFunction=delete&userId=<?= htmlentities($userInformations['id']) ?>" type="button"
                               class="btn btn-primary">
                                <button>Oui</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
endforeach;
?>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>