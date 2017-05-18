<!-- /include/session.inc.php -->
<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


// Check if user is logged on.
if (isset($_SESSION['username'])) {
    $records = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $records->execute([$_SESSION['username']]);
    $results = $records->fetch(PDO::FETCH_ASSOC);

    // Default value to prevent issues / glitches with sessions
    $user = NULL;

    // If user ID is higher than 0, output data to $user
    if (count($results) > 0) {
        $user = $results;
    }
}