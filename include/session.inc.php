<!-- /include/session.inc.php -->
<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');


// Check if user is logged on.
if (isset($_SESSION['user_id'])) {
    $records = $connection->prepare('SELECT * FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    // Default value to prevent issues / glitches with sessions
    $user = NULL;

    // If user ID is higher than 0, output data to $user
    if (count($results) > 0) {
        $user = $results;
    }
}