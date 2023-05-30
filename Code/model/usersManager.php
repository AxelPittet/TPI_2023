<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 30.05.2023
 */


/**
 * This function is designed to register a new user account in the database.
 * @param $userEmailAddress
 * @param $userPsw
 * @param $userFirstName
 * @param $userLastName
 * @param $userPhoneNumber
 * @return bool|null
 */
function registerNewAccount($userEmailAddress, $userPsw, $userFirstName, $userLastName, $userPhoneNumber)
{

    $userPswHash = password_hash($userPsw, PASSWORD_DEFAULT);
    $register = "INSERT INTO users (lastname, firstname, email, phoneNumber, password, userType) VALUES ('$userLastName', '$userFirstName', '$userEmailAddress', '$userPhoneNumber', '$userPswHash', 1)";

    require_once 'model/dbconnector.php';
    $registerResult = executeQueryIUD($register);

    return $registerResult;
}


/**
 * This function is designed to return the type of the user which is currently logged in.
 * @param $userEmailAddress
 * @return int|mixed : get the values of the query result
 */
function getUserType($userEmailAddress) {
    $result = 1;

    $strSeparator = '\'';
    $getUserTypeQuery = 'SELECT usertype FROM users WHERE email =' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbconnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);

    if (count($queryResult) == 1) {
        $result = $queryResult[0]['usertype'];
    }

    return $result;
}


/**
 * This function is designed to check if the values of the login form are matching with an exisiting user.
 * @param $userEmailAddress
 * @param $userPsw
 * @return bool
 */
function isLoginCorrect($userEmailAddress, $userPsw)
{
    $result = false;

    $strSeparator = '\'';
    $loginQuery = 'SELECT * FROM users WHERE email = ' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbconnector.php';
    $queryResult = executeQuerySelect($loginQuery);

    if (count($queryResult) == 1) {
        $userHashPsw = $queryResult[0]['password'];
        if (password_verify($userPsw, $userHashPsw) == true) {
            $result = true;
        } else {
            $result = false;
        }
    } else {
        $result = false;
    }

    return $result;
}


/**
 * This function is designed to check if an email already exists in the users table in the database.
 * @param $userEmailAddress
 * @return bool : contain true if the email already exists or false if not
 */
function emailAlreadyExists($userEmailAddress)
{
    $query = "SELECT * FROM users WHERE email = '$userEmailAddress'";

    require_once 'model/dbconnector.php';
    if (empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}


/**
 * This function is designed to return the id of the user which is currently logged in.
 * @param $userEmailAddress
 * @return int|mixed : get the values of the query result
 */
function getUserId($userEmailAddress) {
    $result = 1;

    $strSeparator = '\'';
    $getUserIdQuery = 'SELECT id FROM users WHERE email =' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbconnector.php';
    $queryResult = executeQuerySelect($getUserIdQuery);

    if (count($queryResult) == 1) {
        $result = $queryResult[0]['id'];
    }

    return $result;
}


function getUserEmailAddress($userId){
    $getUserTypeQuery = "SELECT email FROM users WHERE id = '$userId'";

    require_once 'model/dbconnector.php';
    $getUserTypeResult = executeQuerySelect($getUserTypeQuery);
    return $getUserTypeResult;
}


/**
 * This function is designed to return the values of the users table in the database in an array
 * @return array|null : get the values of the query result
 */
function getUsers(){
    $getUsersQuery = 'SELECT * FROM users';
    require_once "model/dbconnector.php";
    $users = executeQuerySelect($getUsersQuery);
    return $users;
}


/**
 * This function is designed to return the values of a specific row in the users table in the database
 * @param $userEmailAddress : must contain the email of the user which we want the informations
 * @return array|null : get the values of the query result
 */
function getUser($userEmailAddress){
    $strSeparator = '\'';
    $getUserQuery = 'SELECT * FROM users WHERE email = ' . $strSeparator . $userEmailAddress . $strSeparator;
    require_once "model/dbconnector.php";
    $user = executeQuerySelect($getUserQuery);
    return $user;
}


/**
 * This function is designed to update the values of a specific row in the users table in the database
 * @param $userEmailAddress
 * @param $userFirstName
 * @param $userLastName
 * @param $userPhoneNumber
 * @param $userId
 * @return bool|null
 */
function updateUser($userFirstName, $userLastName, $userPhoneNumber, $userId){
    $updateUserQuery = "UPDATE users SET lastname='$userLastName', firstname = '$userFirstName', phonenumber = '$userPhoneNumber' WHERE id = '$userId'";
    require_once "model/dbconnector.php";
    $result = executeQueryIUD($updateUserQuery);
    return $result;
}


/**
 * This function is designed to delete the values of a specific row in the users table in the database
 * @param $userId
 * @return bool|null
 */
function deleteUser($userId){
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    require_once "model/dbconnector.php";
    $result = executeQueryIUD($deleteUserQuery);
    return $result;
}