<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * date : 16.05.2023
 */


/**
 * This function is designed to add the images of a certain location in the database
 * @param $imageName
 * @param $locationId
 * @return bool|null
 */
function addLocationImages($imageName, $locationId)
{
    $addLocationImagesQuery = "INSERT INTO images (name, location_id) VALUES ('$imageName', '$locationId');";
    require_once "model/dbconnector.php";
    $addLocationImagesResult = executeQueryIUD($addLocationImagesQuery);
    return $addLocationImagesResult;
}


/**
 * This function is designed to check if an image name already exists in the locations table in the database.
 * @param $imageName
 * @return bool : contain true if the image name already exists or false if not
 */
function imageAlreadyExists($imageName)
{
    $query = "SELECT * FROM images WHERE name = '$imageName'";

    require_once 'model/dbConnector.php';
    if (empty(executeQuerySelect($query))) {
        return false;
    } else {
        return true;
    }
}


/**
 * This function is designed to return the values of the images table which belong to a certain location
 * @param $locationId
 * @return array|null
 */
function getLocationImages($locationId)
{
    $getLocationImagesQuery = "SELECT * FROM images WHERE location_id = '$locationId'";
    require_once "model/dbconnector.php";
    $locationImages = executeQuerySelect($getLocationImagesQuery);
    return $locationImages;
}


/**
 * This function is designed to delete an image from the database
 * @param $name
 * @return bool|null
 */
function deleteImage($name)
{
    $deleteImageQuery = "DELETE FROM images WHERE name = '$name';";
    require_once "model/dbconnector.php";
    $deleteImageResult = executeQueryIUD($deleteImageQuery);
    return $deleteImageResult;
}