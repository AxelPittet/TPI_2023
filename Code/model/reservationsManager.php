<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 23.05.2023
 */


/**
 * This function is designed to check if a reservationNumber already exists in the reservations table in the database.
 * @param $reservationNumber
 * @return bool : contain true if the reservationNumber already exists or false if not
 */
function reservationNumberAlreadyExists($reservationNumber)
{
    $query = "SELECT * FROM reservations WHERE reservationNumber = '$reservationNumber'";

    require_once 'model/dbconnector.php';
    if (empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}


function getLocationReservations($locationId)
{
    $getLocationReservationsQuery = "SELECT * FROM reservations WHERE location_id = '$locationId';";
    require_once "model/dbconnector.php";
    $getLocationReservationsResult = executeQuerySelect($getLocationReservationsQuery);
    return $getLocationReservationsResult;
}


function bookLocation($reservationNumber, $startDate, $endDate, $price, $locationId, $userId)
{
    $bookLocationQuery = "INSERT INTO reservations (reservationNumber, startDate, endDate, price, location_id, user_id) VALUES ('$reservationNumber', '$startDate', '$endDate', '$price', '$locationId', '$userId')";
    require_once "model/dbconnector.php";
    $bookLocationResult = executeQueryIUD($bookLocationQuery);
    return $bookLocationResult;
}


function deleteReservation($locationId){
    $deleteReservationQuery = "DELETE FROM reservations WHERE location_id = '$locationId';";
    require_once "model/dbconnector.php";
    $deleteReservationResult = executeQueryIUD($deleteReservationQuery);
    return $deleteReservationResult;
}