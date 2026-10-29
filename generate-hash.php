<?php
// Small helper page, used only once to turn our plain text passwords into hashes.
// It is a throwaway tool for the lab, not part of the shop itself.

// The two test accounts and their current plain text passwords.
$plainPasswords = [
    'admin' => 'admin123',
    'ducka' => 'segredo'
];

// For each account we calculate the hashed version and print a ready to use
// SQL command that replaces the plain password with its hash.
echo "<pre>";
foreach ($plainPasswords as $username => $plain) {

    // password_hash turns the password into a long scrambled fingerprint.
    // The same password gives a different looking hash each time, which is fine,
    // because password_verify knows how to check it later.
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    // Print an UPDATE command we can copy straight into phpMyAdmin.
    echo "UPDATE users SET password = '$hash' WHERE username = '$username';\n";
}
echo "</pre>";
?>