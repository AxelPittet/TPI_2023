<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 23.05.2023
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
    header('LOCATION:/index.php?action=home');
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


/**
 * This function is designed to display all the locations of the database
 * @return void
 */
function locations()
{
    require_once "model/locationsManager.php";
    $locations = getLocations();
    require "view/locations.php";
}


/**
 * This function is designed to display a specific location of the database
 * @return void
 */
function showLocation()
{
    $locationNumber = $_GET['locationNumber'];
    require_once "model/locationsManager.php";
    $location = getSpecificLocation($locationNumber);
    require "view/specificLocation.php";
}


/**
 * This function is designed to display the locations of the database which place contain the search done by the user
 * @param $searchRequest : must contain the place the user searched for
 * @return void
 */
function search($searchRequest)
{
    $search = $searchRequest['search'];
    require_once "model/locationsManager.php";
    $locations = getLocationsResearch($search);
    require "view/locations.php";
}


/**
 * This function is designed to display the locations of the database which attributes corresponds to the filters selected by the user
 * @param $filterRequest : at least one of all the values must be set.
 * @return void
 * @throws Exception
 */
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


/**
 * This function is designed for the user to manage his locations. He is able to add a new one and modify/delete an existing one.
 * @param $userLocationsRequest : contains all the values of the add/modify form the user sent. All values must be fitting in the database for the function to work or it will display an error message
 * @param $userLocationsFiles : contains all the files of the add/modify form the user sent. At least one of them must be set for the function to work or it will display an error message
 * @return void
 */
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
                    if (empty($userLocationsRequest['inputLocationPlace'])) {
                        require_once "model/locationsManager.php";
                        $location = getSpecificLocation($userLocationsRequest['inputLocationNumber']);
                        require_once "model/imagesManager.php";
                        $locationImages = getLocationImages($location[0]['id']);
                        require "view/modifyLocation.php";
                    } else {
                        if (isset($userLocationsRequest['inputLocationExistingImage']) || isset($userLocationsFiles['inputLocationImage'])) {
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
                            $imagesToRemove = array();
                            foreach ($userLocationsRequest['inputLocationRemovedImages'] as $inputLocationRemovedImage) {
                                $imagesToRemove[] = $inputLocationRemovedImage;
                            }

                            $testsPassed = false;
                            //form data verification
                            if (strlen($locationName) > 100) {
                                $modifyLocationErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationPlace) > 130) {
                                $modifyLocationErrorMessage = "Le champ lieu dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationDescription) > 500) {
                                $modifyLocationErrorMessage = "Le champ description dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationClientsNb) > 10) {
                                $modifyLocationErrorMessage = "Le nombre maximal de clients est trop élevé !";
                            } else if (strlen($locationPrice) > 7) {
                                $modifyLocationErrorMessage = "Le prix est trop élevé !";
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
                                            $modifyLocationErrorMessage = "Un nom d'image similaire existe déjà pour '$fileName', veuillez la renommer.";
                                            require_once "model/locationsManager.php";
                                            $location = getSpecificLocation($locationNumber);
                                            require_once "model/imagesManager.php";
                                            $locationImages = getLocationImages($location[0]['id']);
                                            require "view/modifyLocation.php";
                                        }
                                        move_uploaded_file($tmpName, $filePath);
                                        $locationImages[] = $filePath;
                                    }
                                }
                                require "model/locationsManager.php";
                                if (modifyLocation($locationNumber, $locationName, $locationPlace, $locationDescription, $locationHousingType, $locationClientsNb, $locationPrice)) {
                                    foreach ($imagesToRemove as $imageToRemove) {
                                        require_once "model/imagesManager.php";
                                        deleteImage($imageToRemove);
                                        unlink($imageToRemove);
                                    }
                                    require_once "model/locationsManager.php";
                                    $locationId = getLocationId($locationNumber);
                                    require_once "model/imagesManager.php";
                                    foreach ($locationImages as $locationImage) {
                                        addLocationImages($locationImage, $locationId[0][0]);
                                    }
                                    require "view/home.php";
                                } else {
                                    $modifyLocationErrorMessage = "Une erreur est apparue, veuillez réessayer.";
                                    require_once "model/locationsManager.php";
                                    $location = getSpecificLocation($locationNumber);
                                    require_once "model/imagesManager.php";
                                    $locationImages = getLocationImages($location[0]['id']);
                                    require "view/modifyLocation.php";
                                }
                            } else {
                                require_once "model/locationsManager.php";
                                $location = getSpecificLocation($locationNumber);
                                require_once "model/imagesManager.php";
                                $locationImages = getLocationImages($location[0]['id']);
                                require "view/modifyLocation.php";
                            }
                        } else {
                            $modifyLocationErrorMessage = "Veuilez rentrer au minimum une image !";
                            require_once "model/locationsManager.php";
                            $location = getSpecificLocation($userLocationsRequest['inputLocationNumber']);
                            require_once "model/imagesManager.php";
                            $locationImages = getLocationImages($location[0]['id']);
                            require "view/modifyLocation.php";
                        }
                    }
                }
                break;
            case 'delete' :
                $locationNumber = $_GET['locationNumber'];
                require_once "model/locationsManager.php";
                $locationId = getLocationId($locationNumber);
                require_once "model/imagesManager.php";
                $images = getLocationImages($locationId[0]['id']);
                foreach ($images as $image) {
                    deleteImage($image['name']);
                }
                deleteLocation($locationNumber);
                require "view/home.php";
                break;
        }
    }
}


/**
 * This function is designed to return a random serial number of 9 caracters.
 * @return string : contains the random serial number that has been generated
 */
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


function booking($bookRequest)
function booking($bookingRequest)
{
    $locationNumber = $_GET['locationNumber'];
    if (empty($bookingRequest)) {
        require_once "model/locationsManager.php";
        $location = getSpecificLocation($locationNumber);
        require "view/booking.php";
    } else {
        $startDate = $bookingRequest['inputStartDate'];
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = $bookingRequest['inputEndDate'];
        $endDate = date("Y-m-d", strtotime($endDate));
        $totalPrice = $bookingRequest['inputTotalPrice'];
        $reservationNumber = generateNumber();
        require_once "model/reservationsManager.php";
        while (reservationNumberAlreadyExists($reservationNumber) == true) {
            $reservationNumber = generateNumber();
        }
        require_once "model/locationsManager.php";
        $locationId = getLocationId($locationNumber);
        require_once "model/usersManager.php";
        $userId = getUserId($_SESSION['userEmailAddress']);
        require_once "model/reservationsManager.php";
        $locationReservations = getLocationReservations($locationId[0]['id']);

        $userDateRange = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );
        foreach ($locationReservations as $locationReservedDateRange) {
            $bddDateRange = new DatePeriod(
                new DateTime($locationReservedDateRange['startDate']),
                new DateInterval('P1D'),
                new DateTime($locationReservedDateRange['endDate'])
            );
            foreach ($userDateRange as $userDate) {
                foreach ($bddDateRange as $bddDate) {
                    if ($userDate == $bddDate) {
                        $reservationErrorMessage = "La réservation n'est pas possible avec les dates choisies, verifiez qu'aucune réservation déjà présente n'entrave la votre !";
                        require_once "model/locationsManager.php";
                        $location = getSpecificLocation($locationNumber);
                        require "view/booking.php";
                    }
                }
            }
        }

        if (bookLocation($reservationNumber, $startDate, $endDate, $totalPrice, $locationId[0]['id'], $userId)) {

            require_once "model/locationsManager.php";
            $location = getSpecificLocation($locationNumber);
            $location = $location[0];
            require_once "model/usersManager.php";
            $userEmailAddress = getUserEmailAddress($userId);

            require 'PHPMailer/src/Exception.php';
            require 'PHPMailer/src/PHPMailer.php';
            require 'PHPMailer/src/SMTP.php';

            $mail = new PHPMailer(TRUE);
            try {
                $mail->isSMTP();
                $mail->Host = 'mail01.swisscenter.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'confirmation@lochabitat.mycpnv.ch';
                $mail->Password = 'Pa$$w0rdLocHabitat';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('confirmation@lochabitat.mycpnv.ch', 'LocHabitat Confirmation');
                $mail->addAddress($_SESSION['userEmailAddress']);

                $mail->isHTML(true);
                $mail->Subject = "Confirmation de commande n " . $reservationNumber;
                $mail->Body = "Vous avez reservez la location " . $location['name'] . " du " . $startDate . " au " . $endDate . " pour le prix de " . $totalPrice . "CHF.";
                $mail->AltBody = "Vous avez reservez la location " . $location['name'] . " du " . $startDate . " au " . $endDate . " pour le prix de " . $totalPrice . "CHF.";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            $mail = new PHPMailer(TRUE);
            try {
                $mail->isSMTP();
                $mail->Host = 'mail01.swisscenter.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'confirmation@lochabitat.mycpnv.ch';
                $mail->Password = 'Pa$$w0rdLocHabitat';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('confirmation@lochabitat.mycpnv.ch', 'LocHabitat Confirmation');
                $mail->addAddress($userEmailAddress[0]['email']);

                $mail->isHTML(true);
                $mail->Subject = "Reservation de la location n " . $locationNumber;
                $mail->Body = "Votre location " . $location['name'] . " a ete reservee du " . $startDate . " au " . $endDate . " pour un prix total de " . $totalPrice . " CHF qui vous seront prochainement verses.";
                $mail->AltBody = "Votre location " . $location['name'] . " a ete reservee du " . $startDate . " au " . $endDate . " pour un prix total de " . $totalPrice . " CHF qui vous seront prochainement verses.";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            require "view/home.php";
        } else {
            $reservationErrorMessage = "Une erreur est apparue, veuillez réessayer s'il vous plait.";
            require_once "model/locationsManager.php";
            $location = getSpecificLocation($locationNumber);
            require "view/booking.php";
        }

    }
}


/**
 * This function is designed to display the right admin menu
 * @param $adminRequest : contain some $_POST values for the use of CRUD forms
 * @return void
 */
function admin($adminRequest, $adminFiles)
{
    if (empty($_GET['adminFunction'])) {
        require "view/admin.php";
    } else {
        switch ($_GET['adminFunction']) {
            case 'users' :
                adminUsers($adminRequest);
                break;
            case 'locations' :
                adminLocations($adminRequest, $adminFiles);
                break;
            default :
                break;
        }
    }
}


/**
 * This function is designed to display the right users CRUD menu / form
 * @param $adminUsersRequest : contain some $_POST values for the use of the users CRUD forms
 * @return void
 */
function adminUsers($adminUsersRequest)
{
    if (empty($_GET['usersFunction'])) {
        require "view/usersAdmin.php";
    } else {
        switch ($_GET['usersFunction']) {
            case 'add' :
                if (empty($adminUsersRequest)) {
                    require "view/addUser.php";
                } else {
                    $userFirstName = $adminUsersRequest ['inputUserFirstName'];
                    $userLastName = $adminUsersRequest ['inputUserLastName'];
                    $userPhoneNumber = $adminUsersRequest ['inputUserPhoneNumber'];
                    $userEmailAddress = $adminUsersRequest['inputUserEmailAddress'];
                    $userPsw = $adminUsersRequest['inputUserPsw'];
                    $userConfirmPsw = $adminUsersRequest['inputUserConfirmPsw'];

                    $testsPassed = false;
                    require_once "model/usersManager.php";
                    if (strlen($userPhoneNumber) > 14) {
                        $addUserErrorMessage = "Le n° de téléphone n'est pas valide !";
                    } else if (strlen($userFirstName) > 80) {
                        $addUserErrorMessage = "Le champ prénom dépasse le nombre de caractères autorisés !";
                    } else if (strlen($userLastName) > 80) {
                        $addUserErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                    } else if (emailAlreadyExists($userEmailAddress)) {
                        $addUserErrorMessage = "Cette adresse mail est déjà utilisée par un autre compte !";
                    } else if ($userPsw != $userConfirmPsw) {
                        $addUserErrorMessage = "Les mots de passe ne correspondent pas !";
                    } else {
                        $testsPassed = true;
                    }

                    if ($testsPassed) {
                        require_once "model/usersManager.php";
                        if (registerNewAccount($userEmailAddress, $userPsw, $userFirstName, $userLastName, $userPhoneNumber)) {
                            $addUserErrorMessage = null;
                            home();
                        } else {
                            $addUserErrorMessage = "L'ajout a rencontré une erreur, merci de réessayer.";
                            require "view/addUser.php";
                        }
                    } else {
                        require "view/addUser.php";
                    }
                }
                break;
            case 'modify' :
                if (empty($adminUsersRequest)) {
                    require_once "model/usersManager.php";
                    $users = getUsers();
                    require "view/modifyUserChoice.php";
                } else {
                    if (empty($adminUsersRequest['inputUserFirstName'])) {
                        require_once "model/usersManager.php";
                        $user = getUser($adminUsersRequest['inputUserEmailAddress']);
                        require "view/modifyUser.php";
                    } else {
                        $userFirstName = $adminUsersRequest ['inputUserFirstName'];
                        $userLastName = $adminUsersRequest ['inputUserLastName'];
                        $userPhoneNumber = $adminUsersRequest ['inputUserPhoneNumber'];
                        $userEmailAddress = $adminUsersRequest['inputUserEmailAddress'];
                        $userId = $adminUsersRequest['inputUserId'];

                        $testsPassed = false;
                        require_once "model/usersManager.php";
                        if (strlen($userPhoneNumber) > 14) {
                            $modifyUserErrorMessage = "Le n° de téléphone n'est pas valide !";
                        } else if (strlen($userFirstName) > 80) {
                            $modifyUserErrorMessage = "Le champ prénom dépasse le nombre de caractères autorisés !";
                        } else if (strlen($userLastName) > 80) {
                            $modifyUserErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                        } else if (emailAlreadyExists($userEmailAddress)) {
                            $modifyUserErrorMessage = "Cette adresse mail est déjà utilisée par un autre compte !";
                        } else {
                            $testsPassed = true;
                        }

                        require_once "model/usersManager.php";
                        if ($testsPassed) {
                            if (updateUser($userEmailAddress, $userFirstName, $userLastName, $userPhoneNumber, $userId)) {
                                home();
                            } else {
                                $modifyUserErrorMessage = "La modification a rencontré une erreur, merci de réessayer.";
                                require_once "model/usersManager.php";
                                $users = getUsers();
                                require "view/modifyUserChoice.php";
                            }
                        } else {
                            $users = getUsers();
                            require "view/modifyUserChoice.php";
                        }
                    }
                }
                break;
            case 'delete' :
                $userId = $_GET['userId'];
                require_once "model/usersManager.php";
                deleteUser($userId);
                home();
                break;
            default :
                break;
        }
    }
}


function adminLocations($adminLocationsRequest, $adminLocationsFiles)
{
    if (empty($_GET['adminLocationsFunction'])) {
        require "view/locationsAdmin.php";
    } else {
        switch ($_GET['adminLocationsFunction']) {
            case 'add' :
                if (empty($adminLocationsRequest)) {
                    require "view/addAdminLocation.php";
                } else {
                    $locationName = $adminLocationsRequest['inputLocationName'];
                    $locationName = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationName);
                    $locationPlace = $adminLocationsRequest['inputLocationPlace'];
                    $locationPlace = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationPlace);
                    $locationDescription = $adminLocationsRequest['inputLocationDescription'];
                    $locationDescription = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationDescription);
                    $locationHousingType = $adminLocationsRequest['inputLocationHousingType'];
                    $locationClientsNb = $adminLocationsRequest['inputLocationClientsNb'];
                    $locationPrice = $adminLocationsRequest['inputLocationPrice'];
                    $locationNumber = generateNumber();
                    require_once "model/locationsManager.php";
                    while (locationNumberAlreadyExists($locationNumber) == true) {
                        $locationNumber = generateNumber();
                    }

                    $testsPassed = false;
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
                        foreach ($adminLocationsFiles['inputLocationImage']['tmp_name'] as $index => $tmpName) {
                            if ($adminLocationsFiles['inputLocationImage']['error'][$index] === UPLOAD_ERR_OK) {
                                $fileName = $adminLocationsFiles['inputLocationImage']['name'][$index];
                                $filePath = $uploadDir . $fileName;

                                require_once "model/imagesManager.php";
                                if (imageAlreadyExists($filePath)) {
                                    $addLocationErrorMessage = "Un nom d'image similaire existe déjà pour '$fileName', veuillez la renommer.";
                                    require "view/addAdminLocation.php";
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
                            require "view/addAdminLocation.php";
                        }
                    } else {
                        require "view/addAdminLocation.php";
                    }
                }
                break;
            case
            'modify' :
                if (empty($adminLocationsRequest)) {
                    require_once "model/locationsManager.php";
                    $locations = getLocations();
                    require "view/modifyAdminLocationChoice.php";
                } else {
                    if (empty($adminLocationsRequest['inputLocationPlace'])) {
                        require_once "model/locationsManager.php";
                        $location = getSpecificLocation($adminLocationsRequest['inputLocationNumber']);
                        require_once "model/imagesManager.php";
                        $locationImages = getLocationImages($location[0]['id']);
                        require "view/modifyAdminLocation.php";
                    } else {
                        if (isset($adminLocationsRequest['inputLocationExistingImage']) || isset($adminLocationsFiles['inputLocationImage'])) {
                            $locationName = $adminLocationsRequest['inputLocationName'];
                            $locationName = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationName);
                            $locationPlace = $adminLocationsRequest['inputLocationPlace'];
                            $locationPlace = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationPlace);
                            $locationDescription = $adminLocationsRequest['inputLocationDescription'];
                            $locationDescription = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $locationDescription);
                            $locationHousingType = $adminLocationsRequest['inputLocationHousingType'];
                            $locationClientsNb = $adminLocationsRequest['inputLocationClientsNb'];
                            $locationPrice = $adminLocationsRequest['inputLocationPrice'];
                            $locationNumber = $adminLocationsRequest['inputLocationNumber'];
                            $imagesToRemove = array();
                            foreach ($adminLocationsRequest['inputLocationRemovedImages'] as $inputLocationRemovedImage) {
                                $imagesToRemove[] = $inputLocationRemovedImage;
                            }

                            $testsPassed = false;
                            if (strlen($locationName) > 100) {
                                $modifyLocationErrorMessage = "Le champ nom dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationPlace) > 130) {
                                $modifyLocationErrorMessage = "Le champ lieu dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationDescription) > 500) {
                                $modifyLocationErrorMessage = "Le champ description dépasse le nombre de caractères autorisés !";
                            } else if (strlen($locationClientsNb) > 10) {
                                $modifyLocationErrorMessage = "Le nombre maximal de clients est trop élevé !";
                            } else if (strlen($locationPrice) > 7) {
                                $modifyLocationErrorMessage = "Le prix est trop élevé !";
                            } else {
                                $testsPassed = true;
                            }

                            if ($testsPassed) {
                                $locationImages = array();
                                $uploadDir = "view/img/";
                                foreach ($adminLocationsFiles['inputLocationImage']['tmp_name'] as $index => $tmpName) {
                                    if ($adminLocationsFiles['inputLocationImage']['error'][$index] === UPLOAD_ERR_OK) {
                                        $fileName = $adminLocationsFiles['inputLocationImage']['name'][$index];
                                        $filePath = $uploadDir . $fileName;

                                        require_once "model/imagesManager.php";
                                        if (imageAlreadyExists($filePath)) {
                                            $modifyLocationErrorMessage = "Un nom d'image similaire existe déjà pour '$fileName', veuillez la renommer.";
                                            require_once "model/locationsManager.php";
                                            $location = getSpecificLocation($locationNumber);
                                            require_once "model/imagesManager.php";
                                            $locationImages = getLocationImages($location[0]['id']);
                                            require "view/modifyAdminLocation.php";
                                        }
                                        move_uploaded_file($tmpName, $filePath);
                                        $locationImages[] = $filePath;
                                    }
                                }
                                require "model/locationsManager.php";
                                if (modifyLocation($locationNumber, $locationName, $locationPlace, $locationDescription, $locationHousingType, $locationClientsNb, $locationPrice)) {
                                    foreach ($imagesToRemove as $imageToRemove) {
                                        require_once "model/imagesManager.php";
                                        deleteImage($imageToRemove);
                                        unlink($imageToRemove);
                                    }
                                    require_once "model/locationsManager.php";
                                    $locationId = getLocationId($locationNumber);
                                    require_once "model/imagesManager.php";
                                    foreach ($locationImages as $locationImage) {
                                        addLocationImages($locationImage, $locationId[0][0]);
                                    }
                                    require "view/home.php";
                                } else {
                                    $modifyLocationErrorMessage = "Une erreur est apparue, veuillez réessayer.";
                                    require_once "model/locationsManager.php";
                                    $location = getSpecificLocation($locationNumber);
                                    require_once "model/imagesManager.php";
                                    $locationImages = getLocationImages($location[0]['id']);
                                    require "view/modifyAdminLocation.php";
                                }
                            } else {
                                require_once "model/locationsManager.php";
                                $location = getSpecificLocation($locationNumber);
                                require_once "model/imagesManager.php";
                                $locationImages = getLocationImages($location[0]['id']);
                                require "view/modifyAdminLocation.php";
                            }
                        } else {
                            $modifyLocationErrorMessage = "Veuilez rentrer au minimum une image !";
                            require_once "model/locationsManager.php";
                            $location = getSpecificLocation($adminLocationsRequest['inputLocationNumber']);
                            require_once "model/imagesManager.php";
                            $locationImages = getLocationImages($location[0]['id']);
                            require "view/modifyAdminLocation.php";
                        }
                    }
                }
                break;
            case 'delete' :
                $locationNumber = $_GET['locationNumber'];
                require_once "model/locationsManager.php";
                $locationId = getLocationId($locationNumber);
                require_once "model/imagesManager.php";
                $images = getLocationImages($locationId[0]['id']);
                foreach ($images as $image) {
                    deleteImage($image['name']);
                }
                $imagesToRemove = array();
                foreach ($adminLocationsRequest['inputLocationRemovedImages'] as $inputLocationRemovedImage) {
                    $imagesToRemove[] = $inputLocationRemovedImage;
                }
                foreach ($imagesToRemove as $imageToRemove) {
                    unlink($imageToRemove);
                }
                require_once "model/reservationsManager.php";
                deleteReservation($locationId[0]['id']);
                require_once "model/locationsManager.php";
                deleteLocation($locationNumber);
                require "view/home.php";
                break;
        }
    }
}