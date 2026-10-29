<?php
// Small lab helper: wipes every comment (including injected attacks) and
// puts back the two clean demo comments. Lets me reset the shop to a fresh
// state before each presentation, without touching phpMyAdmin.
require 'config.php';

// Remove all comments, attacks included.
$connection->query("DELETE FROM comments");

// Put the two original demo comments back.
$connection->query("INSERT INTO comments (author, comment) VALUES
    ('Kinjal', 'Loved the shop, fast delivery!'),
    ('Eugenio', 'Great service, highly recommend.')");

echo "Shop reset. The comments are back to the original two. ";
echo '<a href="index.php">Go to the shop</a>';
?>