<?php
require_once 'config/database.php';
require_once 'config/app_config.php';
require_once 'app/helpers/SessionHelper.php';

SessionHelper::start();

if (!SessionHelper::isLoggedIn()) {
    header("Location: " . BASE_URL . "views/auth/login.php");
    exit();
}

include 'views/dashboard/home.php';
?>
