<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 09.05.2023
 */


/**
 * This function is designed to register a new user account in the database
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
 * This function is designed to return the type of the user which is currently logged in
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
 * This function is designed to check if the values of the login form are matching with an exisiting user
 * @param $userEmailAddress
 * @param $userPsw
 * @return bool
 */
function isLoginCorrect($userEmailAddress, $userPsw)
{
    $result = false;

    $strSeparator = '\'';
    $loginQuery = 'SELECT * FROM users WHERE email = ' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbConnector.php';
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


function emailAlreadyExists($userEmailAddress) {
    $query = "SELECT * FROM users WHERE email = '$userEmailAddress'";

    require_once 'model/dbConnector.php';
    if(empty(executeQuerySelect($query))) {
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