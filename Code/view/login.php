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
            <div class="max-w-screen-md">
                <h1 class="text-5xl font-bold">Authentification</h1>
                <div class="divider before:bg-neutral-50 after:bg-neutral-50"></div>
                <div class="card flex-shrink-0 w-full shadow-2xl bg-base-100">
                    <div class="card-body">
                        <form action="index.php?action=login" method="post">
                            <div class="form-control">

                                <input type="email" placeholder="Adresse email" class="input input-bordered"
                                       name="inputUserEmailAddress" required/>
                            </div>
                            <br>
                            <div class="form-control">

                                <input type="password" placeholder="Mot de passe" class="input input-bordered"
                                       name="inputUserPsw"
                                       required/>
                            </div>
                            <div class="form-control mt-6">
                                <input type="submit" value="S'authentifier" class="btn btn-primary"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center">
                    <p class="py-6">Si vous ne possédez pas encore de compte, veuillez cliquer ci-dessous :</p>
                    <a href="index.php?action=register"><p class="font-bold">Création d'un compte !</p></a>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function showErrorMessage(){
            <?php if (isset($loginErrorMessage)) :?>
            alert("<?= $loginErrorMessage ?>");
            <?php endif;?>
        }
    </script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>