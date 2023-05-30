<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 30.05.2023
 */

ob_start();
?>

    <div class="hero min-h-screen">
        <div class="hero-content text-center">
            <div class="w-screen">
                <h1 class="text-5xl font-bold">Modifier un utilisateur</h1>
                <div class="divider"></div>
                <div class="card-body">
                    <form action="index.php?action=admin&adminFunction=users&usersFunction=modify" method="post">
                        <div class="form-control">
                            <label class="label" for="userEmail">
                                <span class="label-text">Email</span>
                            </label>
                            <select id="userEmail" class="input input-bordered" name="inputUserEmailAddress" required>
                                <?php
                                foreach ($users as $user):
                                ?>
                                    <option value="<?= htmlentities($user['email']) ?>"><?= htmlentities($user['email']) ?></option>
                                    <?php
                                    endforeach;
                                    ?>
                            </select>
                        </div>
                        <div class="form-control mt-6">
                            <input type="submit" value="Modifier l'utilisateur" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function showErrorMessage() {
            <?php if (isset($modifyUserErrorMessage)) :?>
            alert("<?= $modifyUserErrorMessage ?>");
            <?php endif;?>
        }
    </script>

<?php
$content = ob_get_clean();
require "gabarit.php";
?>