<?php

// On verifie que la variable est bien dans l'URL
// dans ce cas ci, message et status
if (isset($_GET['message']) && isset($_GET['status'])) {
    $message = $_GET['message'];
    $status = $_GET['status'];

    echo "<h3 class='text-center $status'>$message</h3>";
}
