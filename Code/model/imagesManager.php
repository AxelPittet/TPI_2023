<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 15.05.2023
 */


function addLocationImages($imageName, $locationId){
    $addLocationImagesQuery = "INSERT INTO images (name, location_id) VALUES ('$imageName', '$locationId');";
    require_once "model/dbconnector.php";
    $addLocationImagesResult = executeQueryIUD($addLocationImagesQuery);
    return $addLocationImagesResult;
}

function imageAlreadyExists($imageName) {
    $query = "SELECT * FROM images WHERE name = '$imageName'";

    require_once 'model/dbConnector.php';
    if(empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}
