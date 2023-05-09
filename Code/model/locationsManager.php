<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 09.05.2023
 */


function getLocations()
{
    $getLocationQuery = "SELECT locations.*, GROUP_CONCAT(Images.name) AS imageNames 
FROM locations 
INNER JOIN images ON images.location_id = locations.id 
GROUP BY locations.id;";
    require_once "model/dbconnector.php";
    $locations = executeQuerySelect($getLocationQuery);
    return $locations;
}