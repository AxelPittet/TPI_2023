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
                <h1 class="text-5xl font-bold">Ajouter un utilisateur</h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="card-body">
                    <form action="index.php?action=admin&adminFunction=users&usersFunction=add" method="post">
                        <div class="form-control">
                            <input type="text" placeholder="Prénom" class="input input-bordered"
                                   name="inputUserFirstName" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="text" placeholder="Nom" class="input input-bordered"
                                   name="inputUserLastName" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="number" placeholder="N° de téléphone" class="input input-bordered"
                                   name="inputUserPhoneNumber" maxlength="15" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="email" placeholder="Adresse email" class="input input-bordered"
                                   name="inputUserEmailAddress" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="password" placeholder="Mot de passe" class="input input-bordered"
                                   name="inputUserPsw" required/>
                        </div>
                        <br>
                        <div class="form-control">
                            <input type="password" placeholder="Confirmez le mot de passe" class="input input-bordered"
                                   name="inputUserConfirmPsw" required/>
                        </div>
                        <div class="form-control mt-6">
                            <input type="submit" value="Ajouter l'utilisateur" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function showErrorMessage() {
            <?php if (isset($addUserErrorMessage)) :?>
            alert("<?= $addUserErrorMessage ?>");
            <?php endif;?>
        }
    </script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>