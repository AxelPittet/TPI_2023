<?php
/**
 * author : Axel Pittet
 * project : TPI 2023
 * save date : 05.05.2023
 */

session_start();
require "controller/controller.php";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'home' :
            home();
            break;
        default :
            home();
    }
} else {
    home();
}
