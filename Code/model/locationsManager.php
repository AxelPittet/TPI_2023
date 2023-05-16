<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 16.05.2023
 */


function getLocations()
{
    $getLocationsQuery = "SELECT locations.*, GROUP_CONCAT(images.name) AS imageNames 
FROM locations 
INNER JOIN images ON images.location_id = locations.id 
GROUP BY locations.id;";
    require_once "model/dbconnector.php";
    $locations = executeQuerySelect($getLocationsQuery);
    return $locations;
}


function getSpecificLocation($locationNumber)
{
    $getSpecificLocationQuery = "SELECT locations.*, GROUP_CONCAT(DISTINCT images.name) AS imageNames,
GROUP_CONCAT(DISTINCT reservations.startDate) AS startDates,
GROUP_CONCAT(DISTINCT reservations.endDate) AS endDates
FROM locations
INNER JOIN images ON images.location_id = locations.id
LEFT JOIN reservations ON reservations.location_id = locations.id
WHERE locations.locationNumber = '$locationNumber'
GROUP BY locations.id;";
    require_once "model/dbconnector.php";
    $specificLocation = executeQuerySelect($getSpecificLocationQuery);
    return $specificLocation;
}


function getLocationsResearch($search)
{
    $getLocationsResearch = "SELECT locations.*, GROUP_CONCAT(DISTINCT images.name) AS imageNames,
GROUP_CONCAT(DISTINCT reservations.startDate) AS startDates,
GROUP_CONCAT(DISTINCT reservations.endDate) AS endDates
FROM locations
INNER JOIN images ON images.location_id = locations.id
LEFT JOIN reservations ON reservations.location_id = locations.id
WHERE locations.place LIKE '%$search%'
GROUP BY locations.id;";
    require_once "model/dbconnector.php";
    $locationsResearch = executeQuerySelect($getLocationsResearch);
    return $locationsResearch;
}


function getLocationsFiltered($place, $nbOfClients, $checkboxHouse, $checkboxApartment)
{
    $getLocationsFiltered = "SELECT locations.*, GROUP_CONCAT(DISTINCT images.name) AS imageNames,
GROUP_CONCAT(DISTINCT reservations.startDate) AS startDates,
GROUP_CONCAT(DISTINCT reservations.endDate) AS endDates
FROM locations
INNER JOIN images ON images.location_id = locations.id
LEFT JOIN reservations ON reservations.location_id = locations.id
WHERE ";
    $count = 0;
    if ($place != '') {
        $getLocationsFiltered = $getLocationsFiltered . "locations.place LIKE '%$place%'";
        $count += 1;
    }
    if ($count != 0) {
        $getLocationsFiltered = $getLocationsFiltered . " AND ";
    }
    $getLocationsFiltered = $getLocationsFiltered . "locations.maximumNbOfClients >= '$nbOfClients'";

    if ($checkboxHouse != '' || $checkboxApartment != '') {
        if ($checkboxHouse != '') {
            $getLocationsFiltered = $getLocationsFiltered . " AND locations.housingType = 'Maison'";
        } else {
            $getLocationsFiltered = $getLocationsFiltered . " AND locations.housingType = 'Appartement'";
        }
    }
    $getLocationsFiltered = $getLocationsFiltered . " GROUP BY locations.id;";

    require_once "model/dbconnector.php";
    $locationsFiltered = executeQuerySelect($getLocationsFiltered);
    return $locationsFiltered;
}

function addLocation($locationNumber, $name, $place, $description, $housingType, $clientsNb, $price, $userId){
    $addLocationQuery = "INSERT INTO locations (locationNumber, name, place, description, housingType, maximumNbOfClients, pricePerNight, user_id) VALUES ('$locationNumber', '$name', '$place', '$description', '$housingType', '$clientsNb', '$price', '$userId');";
    require_once 'model/dbconnector.php';
    $addLocationResult = executeQueryIUD($addLocationQuery);
    return $addLocationResult;
}

function getLocationId($locationNumber){
    $getLocationIdQuery = "SELECT id FROM locations WHERE locationNumber = '$locationNumber'";
    require_once "model/dbconnector.php";
    $locationId = executeQuerySelect($getLocationIdQuery);
    return $locationId;
}

function locationNumberAlreadyExists($locationNumber) {
    $query = "SELECT * FROM locations WHERE locationNumber = '$locationNumber'";

    require_once 'model/dbConnector.php';
    if(empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}

function getUserLocations($userId){
    $getUserLocationsQuery = "SELECT locationNumber, name FROM locations WHERE user_id = '$userId';";
    require_once "model/dbconnector.php";
    $userLocations = executeQuerySelect($getUserLocationsQuery);
    return $userLocations;
}


/**
 * This function is designed to modify a location from the database
 * @param $locationNumber
 * @param $name
 * @param $place
 * @param $description
 * @param $housingType
 * @param $clientsNb
 * @param $price
 * @return bool|null
 */
function modifyLocation($locationNumber, $name, $place, $description, $housingType, $clientsNb, $price)
{
    $modifyLocationQuery = "UPDATE locations SET name = '$name', place = '$place', description = '$description', housingType = '$housingType', maximumNbOfClients = '$clientsNb', pricePerNight = '$price' WHERE locationNumber = '$locationNumber';";
    require_once "model/dbconnector.php";
    $modifyLocationResult = executeQueryIUD($modifyLocationQuery);
    return $modifyLocationResult;
}
}