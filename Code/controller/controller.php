<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 15.05.2023
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
        if (strlen($userPhoneNumber) > 14) {
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


function filter($filterRequest)
{
    $place = $filterRequest['inputPlace'];
    $startDate = $filterRequest['inputStartDate'];
    $endDate = $filterRequest['inputEndDate'];
    $nbOfClients = $filterRequest['inputClientsRange'];
    $checkboxHouse = $filterRequest['inputCheckboxHouse'];
    $checkboxApartment = $filterRequest['inputCheckboxApartment'];

    require_once "model/locationsManager.php";
    $locations = getLocationsFiltered($place, $nbOfClients, $checkboxHouse, $checkboxApartment);

    if ($startDate != '' && $endDate != '') {
        $userDateRange = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );
        $arrayCount = 0;
        foreach ($locations as $location) {
            $startDates = explode(',', $location['startDates']);
            $endDates = explode(',', $location['endDates']);
            $arrayCount2 = 0;
            foreach ($startDates as $startDate) {
                $bddDateRange = new DatePeriod(
                    new DateTime($startDate),
                    new DateInterval('P1D'),
                    new DateTime($endDates[$arrayCount2])
                );
                foreach ($userDateRange as $userDate) {
                    foreach ($bddDateRange as $bddDate) {
                        if ($userDate == $bddDate) {
                            unset($locations[$arrayCount]);
                        }
                    }
                }
                unset($bddDateRange);
                $arrayCount2 += 1;
            }
            $arrayCount += 1;
        }
    }
    require "view/locations.php";
}


function userLocations($userLocationsRequest, $userLocationsFiles)
{
    if (empty($_GET['userLocationsFunction'])) {
        require "view/userLocations.php";
    } else {
        switch ($_GET['userLocationsFunction']) {
            case 'add' :
                if (empty($userLocationsRequest)) {
                    require "view/addLocation.php";
                } else {
                    $locationName = $userLocationsRequest['inputLocationName'];
                    $locationName = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationName);
                    $locationPlace = $userLocationsRequest['inputLocationPlace'];
                    $locationPlace = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationPlace);
                    $locationDescription = $userLocationsRequest['inputLocationDescription'];
                    $locationDescription = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationDescription);
                    $locationHousingType = $userLocationsRequest['inputLocationHousingType'];
                    $locationClientsNb = $userLocationsRequest['inputLocationClientsNb'];
                    $locationPrice = $userLocationsRequest['inputLocationPrice'];
                    $locationNumber = generateNumber();
                    require_once "model/locationsManager.php";
                    while (locationNumberAlreadyExists($locationNumber) == true) {
                        $locationNumber = generateNumber();
                    }

                    $testsPassed = false;
                    //form data verification
                    if (strlen($locationName) > 100) {
                        $addLocationErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                    } else if (strlen($locationPlace) > 130) {
                        $addLocationErrorMessage = "Le champ lieu dépasse le nombre de caractères autorisés !";
                    } else if (strlen($locationDescription) > 500) {
                        $addLocationErrorMessage = "Le champ description dépasse le nombre de caractères autorisés !";
                    } else if (strlen($locationClientsNb) > 10) {
                        $addLocationErrorMessage = "Le nombre maximal de clients est trop élevé !";
                    } else if (strlen($locationPrice) > 7) {
                        $addLocationErrorMessage = "Le prix est trop élevé !";
                    } else {
                        $testsPassed = true;
                    }

                    if ($testsPassed) {
                        $locationImages = array();
                        $uploadDir = "view/img/";
                        foreach ($userLocationsFiles['inputLocationImage']['tmp_name'] as $index => $tmpName) {
                            if ($userLocationsFiles['inputLocationImage']['error'][$index] === UPLOAD_ERR_OK) {
                                $fileName = $userLocationsFiles['inputLocationImage']['name'][$index];
                                $filePath = $uploadDir . $fileName;

                                require_once "model/imagesManager.php";
                                if (imageAlreadyExists($filePath)) {
                                    $addLocationErrorMessage = "Un nom d'image similaire existe déjà pour '$fileName', veuillez la renommer.";
                                    require "view/addLocation.php";
                                }
                                move_uploaded_file($tmpName, $filePath);

                                $locationImages[] = $filePath;
                            }
                        }

                        require_once "model/usersManager.php";
                        $userId = getUserId($_SESSION['userEmailAddress']);

                        require_once "model/locationsManager.php";
                        if (addLocation($locationNumber, $locationName, $locationPlace, $locationDescription, $locationHousingType, $locationClientsNb, $locationPrice, $userId)) {
                            $locationId = getLocationId($locationNumber);
                            require_once "model/imagesManager.php";
                            foreach ($locationImages as $locationImage) {
                                addLocationImages($locationImage, $locationId[0][0]);
                            }
                            require "view/home.php";
                        } else {
                            $addLocationErrorMessage = "Une erreur est apparue, veuillez réessayer.";
                            require "view/addLocation.php";
                        }
                    }
                    require "view/addLocation.php";
                }
                break;
            case
            'modify' :
                if (empty($userLocationsRequest)) {
                    require_once "model/usersManager.php";
                    $userId = getUserId($_SESSION['userEmailAddress']);
                    require_once "model/locationsManager.php";
                    $userLocations = getUserLocations($userId);
                    require "view/modifyLocationChoice.php";
                } else {
                    if (empty($userLocationsRequest['inputLocationPlace'])){
                        require_once "model/locationsManager.php";
                        $location = getSpecificLocation($userLocationsRequest['inputLocationNumber']);
                        require_once "model/imagesManager.php";
                        $locationImages = getLocationImages($location[0]['id']);
                        require "view/modifyLocation.php";
                    } else {
                        if (isset($userLocationsRequest['inputLocationImage'])){
                            $locationName = $userLocationsRequest['inputLocationName'];
                            $locationName = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationName);
                            $locationPlace = $userLocationsRequest['inputLocationPlace'];
                            $locationPlace = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationPlace);
                            $locationDescription = $userLocationsRequest['inputLocationDescription'];
                            $locationDescription = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationDescription);
                            $locationHousingType = $userLocationsRequest['inputLocationHousingType'];
                            $locationClientsNb = $userLocationsRequest['inputLocationClientsNb'];
                            $locationPrice = $userLocationsRequest['inputLocationPrice'];
                            $locationNumber = $userLocationsRequest['inputLocationNumber'];

                            $testsPassed = false;
                            //form data verification
                            if (strlen($locationName) > 100) {
                                $addLocationErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationPlace) > 130) {
                                $addLocationErrorMessage = "Le champ lieu dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationDescription) > 500) {
                                $addLocationErrorMessage = "Le champ description dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationClientsNb) > 10) {
                                $addLocationErrorMessage = "Le nombre maximal de clients est trop élevé !";
                            } else if (strlen($locationPrice) > 7) {
                                $addLocationErrorMessage = "Le prix est trop élevé !";
                            } else {
                                $testsPassed = true;
                            }

                            if ($testsPassed){
                                $locationImages = array();
                                $uploadDir = "view/img/";
                                foreach ($userLocationsFiles['inputLocationImage']['tmp_name'] as $index => $tmpName) {
                                    if ($userLocationsFiles['inputLocationImage']['error'][$index] === UPLOAD_ERR_OK) {
                                        $fileName = $userLocationsFiles['inputLocationImage']['name'][$index];
                                        $filePath = $uploadDir . $fileName;

                                        require_once "model/imagesManager.php";
                                        if (imageAlreadyExists($filePath)) {
                                            $addLocationErrorMessage = "Un nom d'image similaire existe déjà pour '$fileName', veuillez la renommer.";
                                            require "view/addLocation.php";
                                        }
                                        move_uploaded_file($tmpName, $filePath);

                                        $locationImages[] = $filePath;
                                    }
                                }

                                require_once "model/locationsManager.php";


                            } else {
                                require "view/modifyLocation.php";
                            }
                        } else {
                            $modifyLocationError = "Veuilez rentrer au minimum une image !";
                            require "view/modifyLocation.php";
                        }
                    }
                }
                break;
            case 'delete' :

                break;
        }
    }
}


function generateNumber()
{
    $chars = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    $sn = '';
    $max = count($chars) - 1;
    for ($i = 0; $i < 9; $i++) {
        $sn .= $chars[rand(0, $max)];
    }
    return $sn;
}