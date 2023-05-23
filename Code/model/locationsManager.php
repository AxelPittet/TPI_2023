<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 16.05.2023
 */


/**
 * This function is designed to return all the values of the locations in the database.
 * @return array|null : contains the values of the locations table in the database
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


/**
 * This function is designed to return the values of a specific location in the database.
 * @param $locationNumber : contain the locationNumber of the location we want to get in the database
 * @return array|null : contains the values of the specific location
 */
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


/**
 * This function is designed to return the values of the locations table in the database which attribute 'place' contain the search
 * @param $search :
 * @return array|null : contain the values of the locations which corresponds the search
 */
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


/**
 * This function is designed to return the values of the locations table in the database which attributes correspond with the filters
 * @param $place
 * @param $nbOfClients
 * @param $checkboxHouse
 * @param $checkboxApartment
 * @return array|null : contain the values of the locations which corresponds the filters
 */
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


/**
 * This function is designed to add a new location in the database
 * @param $locationNumber
 * @param $name
 * @param $place
 * @param $description
 * @param $housingType
 * @param $clientsNb
 * @param $price
 * @param $userId
 * @return bool|null
 */
function addLocation($locationNumber, $name, $place, $description, $housingType, $clientsNb, $price, $userId)
{
    $addLocationQuery = "INSERT INTO locations (locationNumber, name, place, description, housingType, maximumNbOfClients, pricePerNight, user_id) VALUES ('$locationNumber', '$name', '$place', '$description', '$housingType', '$clientsNb', '$price', '$userId');";
    require_once 'model/dbconnector.php';
    $addLocationResult = executeQueryIUD($addLocationQuery);
    return $addLocationResult;
}


/**
 * This function is designed to return the id of a specific location in the database
 * @param $locationNumber
 * @return array|null
 */
function getLocationId($locationNumber)
{
    $getLocationIdQuery = "SELECT id FROM locations WHERE locationNumber = '$locationNumber'";
    require_once "model/dbconnector.php";
    $locationId = executeQuerySelect($getLocationIdQuery);
    return $locationId;
}


/**
 * This function is designed to check if a locationNumber already exists in the locations table in the database.
 * @param $locationNumber
 * @return bool : contain true if the locationNumber already exists or false if not
 */
function locationNumberAlreadyExists($locationNumber)
{
    $query = "SELECT * FROM locations WHERE locationNumber = '$locationNumber'";

    require_once 'model/dbconnector.php';
    if (empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}


/**
 * This function is designed to return all the locations in the database which have been created by the connected user
 * @param $userId
 * @return array|null
 */
function getUserLocations($userId)
{
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


/**
 * This function is designed to delete a location from the database
 * @param $locationNumber
 * @return bool|null
 */
function deleteLocation($locationNumber){
    $deleteLocationQuery = "DELETE FROM locations WHERE locationNumber = '$locationNumber'";
    require_once "model/dbconnector.php";
    $deleteLocationResult = executeQueryIUD($deleteLocationQuery);
    return $deleteLocationResult;
}