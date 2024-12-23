<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'set' && isset($_GET['name'])) {
        $cookieValue = $_GET['name'];
        setcookie('example_cookie', $cookieValue, time() + (86400 * 7), "/"); // Set cookie for 7 days
        echo "Cookie has been set with value: $cookieValue";
    } elseif ($action === 'get') {
        if (isset($_COOKIE['example_cookie'])) {
            echo "Current cookie value: " . $_COOKIE['example_cookie'];
        } else {
            echo "Cookie is not set.";
        }
    } elseif ($action === 'delete') {
        setcookie('example_cookie', '', time() - 3600, "/"); // Expire the cookie
        echo "Cookie has been deleted.";
    } else {
        echo "Invalid action.";
    }
} else {
    echo "No action provided.";
}
?>