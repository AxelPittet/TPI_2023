<?php
/**
 * author : Axel Pittet
 * project : TPI 2023 - Loc'Habitat
 * save date : 09.05.2023
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
        default :
            home();
    }
} else {
    home();
}
