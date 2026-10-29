<?php
// Bring in the engine room for this page.
// From here on we already have $mode and $connection ready to use.
require 'config.php';

// If the visitor clicked the send button, this is where we receive the comment.
if (isset($_POST['send_comment'])) {

    // Grab the name and the text the person wrote in the form.
    $author = $_POST['author'];
    $text = $_POST['comment'];

    // We save the comment using a prepared statement in both modes now, so that a
    // stray apostrophe in a comment no longer breaks the SQL or opens an injection.
    // The real XSS defence, though, happens later, when we show the comment.
    $query = $connection->prepare("INSERT INTO comments (author, comment) VALUES (?, ?)");
    $query->bind_param("ss", $author, $text);
    $query->execute();
}

// Now we go to the shelf and fetch every comment to display it, newest one first.
$result = $connection->query("SELECT author, comment FROM comments ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ducka's Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- The top bar, which stays the same across every page. -->
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
        <h2>Leave a comment</h2>

        <!-- This form sends whatever the person types back to this same page. -->
        <form method="post">
            <input type="text" name="author" placeholder="Your name">
            <textarea name="comment" placeholder="Write your comment here"></textarea>
            <button type="submit" name="send_comment">Post comment</button>
        </form>

        <h2>Customer comments</h2>

        <?php
        // We walk through the comments we fetched, one by one, and print them.
        while ($row = $result->fetch_assoc()) {

            if ($mode === 'secure') {
                // SECURE VERSION. Before putting the comment on the page we run it
                // through htmlspecialchars, which turns dangerous characters like <
                // into a harmless printable version. A <script> tag then shows up as
                // plain visible text instead of running. The attacker writes code,
                // the visitor only ever sees text.
                $safeAuthor = htmlspecialchars($row['author']);
                $safeComment = htmlspecialchars($row['comment']);
            } else {
                // VULNERABLE VERSION. We print the comment exactly as it came in,
                // so any code inside it runs in the visitor's browser.
                $safeAuthor = $row['author'];
                $safeComment = $row['comment'];
            }

            echo '<div class="comment">';
            echo '<strong>' . $safeAuthor . '</strong>';
            echo '<p>' . $safeComment . '</p>';
            echo '</div>';
        }
        ?>
    </div>

</body>
</html>