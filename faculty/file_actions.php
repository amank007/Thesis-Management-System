<?php
include_once('../config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["mark_as_read"])) {
        // Perform action for "Mark as Read"
        $file_id = mysqli_real_escape_string($conn, $_POST["file_id"]);
        updateFileStatus($conn, $file_id, 'read');
        echo "File marked as read.";
    } elseif (isset($_POST["decline"])) {
        // Perform action for "Decline"
        $file_id = mysqli_real_escape_string($conn, $_POST["file_id"]);
        updateFileStatus($conn, $file_id, 'declined');
        echo "File declined.";
    } elseif (isset($_POST["forward_to_dean"])) {
        // Perform action for "Forward to Dean"
        $file_id = mysqli_real_escape_string($conn, $_POST["file_id"]);
        updateFileStatus($conn, $file_id, 'forwardedtodean');
        echo "File forwarded to Dean.";
    }
}

function updateFileStatus($conn, $file_id, $status) {
    $status = mysqli_real_escape_string($conn, $status);
    $updateQuery = "INSERT INTO file_status (file_id, status, timestamp_status, by_email)
                    VALUES ('$file_id', '$status', CURRENT_TIMESTAMP, '{$_SESSION['email']}')
                    ON DUPLICATE KEY UPDATE status = '$status', timestamp_status = CURRENT_TIMESTAMP";
    mysqli_query($conn, $updateQuery);
}
?>
