<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 09.05.2023
 */


function getLocations()
{
    $getLocationQuery = "SELECT locations.*, GROUP_CONCAT(Images.name) AS imageNames 
    $getLocationQuery = "SELECT locations.*, GROUP_CONCAT(images.name) AS imageNames 
FROM locations 
INNER JOIN images ON images.location_id = locations.id 
GROUP BY locations.id;";
    require_once "model/dbconnector.php";
    $locations = executeQuerySelect($getLocationQuery);
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
}