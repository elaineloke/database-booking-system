<!-- destroy the current session and direct user to index.php on a new session -->
<?php
    session_start();
    session_destroy();
    header("Location: index.php");
    exit();
?>