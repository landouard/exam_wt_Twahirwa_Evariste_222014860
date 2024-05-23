<?php
    // Connection details
    $host = "localhost";
    $user = "evariste";
    $pass = "222005870";
    $database = "food";

    // Creating connection
    $connection = new mysqli($host, $user, $pass, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }