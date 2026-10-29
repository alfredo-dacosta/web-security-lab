<?php
// Temporary while we debug: show errors on screen instead of hiding them.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// This file is the engine room of our shop.
// Every other page starts by calling this one, so they all know which mode
// we are in and already have a working database connection ready to use.

// The main switch of the lab.
// In 'vulnerable' mode the shop runs with its flaws exposed; in 'secure' mode
// it applies the defences. Switch between 'vulnerable' and 'secure' to compare
// both versions of the shop.
$mode = 'vulnerable';

// Now we open the connection to the database we created in phpMyAdmin.
// Think of it as picking up the phone to call the pantry whenever we need
// to fetch or store something. On XAMPP the user is 'root' with an empty password.
$connection = new mysqli('localhost', 'root', '', 'lab_seguranca');

// If the call fails there is no point carrying on. We stop right here and say why,
// so we are not left guessing what went wrong.
if ($connection->connect_error) {
    die('Could not connect to the database: ' . $connection->connect_error);
}

// This makes sure accents and special characters show up correctly on the page.
$connection->set_charset('utf8mb4');
?>