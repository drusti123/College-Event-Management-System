<?php
// Database connection and shared helpers
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "college_event_management";

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database connection failed. Import database.sql in phpMyAdmin and check config.php. Details: " . htmlspecialchars($e->getMessage()));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function require_login() {
    if (empty($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}
function flash($message, $type = "success") {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>';
        unset($_SESSION['flash']);
    }
}
