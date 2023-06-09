<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 23.05.2023
 */

session_start();
require "controller/controller.php";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'home' :
            home();
            break;
        case 'register' :
            register($_POST);
            break;
        case 'login' :
            login($_POST);
            break;
        case 'logout' :
            logout();
            break;
        case 'locations' :
            locations();
            break;
        case 'showLocation' :
            showLocation();
            break;
        case 'search' :
            search($_GET);
            break;
        case 'filters' :
            filter($_POST);
            break;
        case 'userLocations' :
            userLocations($_POST, $_FILES);
            break;
        case 'booking' :
            booking($_POST);
            break;
        case 'admin' :
            admin($_POST, $_FILES);
            break;
        default :
            home();
    }
} else {
    home();
}
