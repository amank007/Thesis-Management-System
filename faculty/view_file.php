<?php
// Include your database connection code
include_once('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['file_id'])) {
    $file_id = mysqli_real_escape_string($conn, $_GET['file_id']);

    // Fetch file details from the database
    $fileQuery = "SELECT * FROM uploads WHERE file_id = ?";
    $stmtFile = mysqli_prepare($conn, $fileQuery);
    mysqli_stmt_bind_param($stmtFile, "s", $file_id);
    mysqli_stmt_execute($stmtFile);
    $resultFile = mysqli_stmt_get_result($stmtFile);

    if ($rowFile = mysqli_fetch_assoc($resultFile)) {
        // Display PDF file
        header("Content-type: application/pdf");
        readfile($rowFile['file_path']);
    } else {
        echo "File not found.";
    }

    // Close the statement
    mysqli_stmt_close($stmtFile);
} else {
    // Redirect if file_id is not provided
    header("Location: deanpg_files.php");
    exit();
}
?>
