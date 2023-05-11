<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 09.05.2023
 */

/**
 * This function is designed to display the "home" view
 * @return void
 */
function home()
{
    require "view/home.php";
}


/**
 * This function is designed to register a new user.
 * @param $registerRequest : all values must be set and both passwords must be the same for the register to work. If passwords are not the same or the email is already exisiting, it will display an error message. If the values aren't all set, it will display the register form.
 * @return void
 */
function register($registerRequest)
{
    if (isset($registerRequest['inputUserEmailAddress']) && isset($registerRequest['inputUserPsw']) && isset($registerRequest['inputUserConfirmPsw'])
        && isset($registerRequest['inputUserFirstName']) && isset($registerRequest['inputUserLastName']) && isset($registerRequest['inputUserPhoneNumber'])) {

        $userFirstName = $registerRequest ['inputUserFirstName'];
        $userLastName = $registerRequest ['inputUserLastName'];
        $userPhoneNumber = $registerRequest ['inputUserPhoneNumber'];
        $userEmailAddress = $registerRequest['inputUserEmailAddress'];
        $userPsw = $registerRequest['inputUserPsw'];
        $userConfirmPsw = $registerRequest['inputUserConfirmPsw'];

        $testsPassed = false;
        //form data verification
        require_once "model/usersManager.php";
        if (strlen($userPhoneNumber) > 15) {
            $registerErrorMessage = "Le n° de téléphone n'est pas valide !";
        } else if (strlen($userFirstName) > 80) {
            $registerErrorMessage = "Le champ prénom dépasse le nombre de caractères autorisés !";
        } else if (strlen($userLastName) > 80) {
            $registerErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
        } else if (emailAlreadyExists($userEmailAddress)) {
            $registerErrorMessage = "Cette adresse mail est déjà utilisée par un autre compte !";
        } else if ($userPsw != $userConfirmPsw) {
            $registerErrorMessage = "Les mots de passe ne correspondent pas !";
        } else {
            $testsPassed = true;
        }

        if ($testsPassed) {
            require_once "model/usersManager.php";
            if (registerNewAccount($userEmailAddress, $userPsw, $userFirstName, $userLastName, $userPhoneNumber)) {
                $userType = getUserType($userEmailAddress);
                createSession($userEmailAddress, $userType);
                $registerErrorMessage = null;
                home();
            } else {
                $registerErrorMessage = "L'inscription n'est pas possible avec les valeurs saisies !";
                require "view/register.php";
            }
        } else {
            require "view/register.php";
        }
    } else {
        $registerErrorMessage = null;
        require "view/register.php";
    }
}


/**
 * This function is designed to log in an exisiting user.
 * @param $loginRequest : all values must be set and must match with a user in the database for the user to be logged in. If it does not match, it will display an error message. If all the values aren't set, it will display the login form.
 * @return void
 */
function login($loginRequest)
{
    if (isset($loginRequest['inputUserEmailAddress']) && isset($loginRequest['inputUserPsw'])) {
        $userEmailAddress = $loginRequest['inputUserEmailAddress'];
        $userPsw = $loginRequest['inputUserPsw'];
        require_once "model/usersManager.php";
        if (isLoginCorrect($userEmailAddress, $userPsw)) {
            $userType = getUserType($userEmailAddress);
            createSession($userEmailAddress, $userType);
            $loginErrorMessage = null;
            home();
        } else {
            $loginErrorMessage = "Cette combinaison n'existe pas !";
            require "view/login.php";
        }
    } else {
        $loginErrorMessage = null;
        require "view/login.php";
    }
}


/**
 * This function is designed to log out a user by resetting the $_SESSION variable
 * @return void
 */
function logout()
{
    session_destroy();
    header('LOCATION:/home');
}


/**
 * This function is designed create a session for a user after a login or register
 * @param $userEmailAddress : must contain the email that was used to log in or register
 * @param $userType : must contain an int which is equal to 1 if this is a normal user or 2 if this is an admin
 * @return void
 */
function createSession($userEmailAddress, $userType)
{
    $_SESSION['userEmailAddress'] = $userEmailAddress;
    $_SESSION['userType'] = $userType;
}


function locations(){
function locations()
{
    require_once "model/locationsManager.php";
    $locations = getLocations();
    require "view/locations.php";
}


function showLocation()
{
    $locationNumber = $_GET['locationNumber'];
    require_once "model/locationsManager.php";
    $location = getSpecificLocation($locationNumber);
    require "view/specificLocation.php";
}


function search($searchRequest)
{
    $search = $searchRequest['search'];
    require_once "model/locationsManager.php";
    $locations = getLocationsResearch($search);
    require "view/locations.php";
}
