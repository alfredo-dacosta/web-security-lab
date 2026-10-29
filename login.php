<?php
// Again we bring in the engine room, to have the mode and the connection ready.
require 'config.php';

// This variable will hold the message we show after the person tries to log in.
$message = '';

// If the person clicked the login button, we handle the sign in here.
if (isset($_POST['login'])) {

    // Fetch whatever was typed into the username and password boxes.
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($mode === 'secure') {

        // SECURE VERSION.
        // First, a prepared statement stops SQL injection, just like before.
        // Notice we now look the user up by username only, and fetch their stored
        // password hash. We no longer try to match the password inside the query.
        $query = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        // If we found that user, we check the typed password against the stored hash.
        // password_verify recalculates the fingerprint of what was typed and compares
        // it with the saved one. The real password is never stored anywhere.
        if ($result && $result->num_rows > 0) {
            $account = $result->fetch_assoc();
            if (password_verify($password, $account['password'])) {
                $message = 'Welcome back, ' . $account['username'] . '. You are now logged in.';
            } else {
                $message = 'Wrong username or password. Please try again.';
            }
        } else {
            $message = 'Wrong username or password. Please try again.';
        }

    } else {

        // VULNERABLE VERSION, the old careless way.
        // Pastes the input straight into the command and compares the plain password,
        // which is both injectable and relies on passwords stored in plain text.
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            $account = $result->fetch_assoc();
            $message = 'Welcome back, ' . $account['username'] . '. You are now logged in.';
        } else {
            $message = 'Wrong username or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login to Ducka's Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="topbar">
        <div>
            <strong>Ducka's Shop</strong>
            <span class="mode-badge">Mode: <?php echo $mode; ?></span>
        </div>
        <div>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="content">
        <h2>Log in to your account</h2>

        <!-- The login form. Sends the username and password to this same page. -->
        <form method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="login">Login</button>
        </form>

        <?php
        // We only show the message if there is something to say,
        // that is, once the person has already tried to log in.
        if ($message !== '') {
            echo '<p>' . $message . '</p>';
        }
        ?>
    </div>

</body>
</html>