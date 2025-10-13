<?php
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../../config/database.php';

AuthController::login($conn);
?>
