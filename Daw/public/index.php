<?php

namespace App; // Namespace of the app root

require("../core/Kernel.php"); // include the framework kernel


session_start(); // Start sessions

date_default_timezone_set('Europe/Paris'); // Define TimeZone

// create Kernel with global information
$kernel = new Kernel(
    $_SERVER,
    $_SESSION,
    $_POST,
    $_GET
);


$kernel->init() // Initialization
       ->process(); // Work
