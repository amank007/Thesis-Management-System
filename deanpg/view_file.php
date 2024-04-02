<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();
include('deanpg_navbar.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['file_id'])) {
    $file_id = mysqli_real_escape_string($conn, $_POST['file_id']);

    // Fetch file details from the database
    $fileQuery = "SELECT * FROM uploads WHERE file_id = ?";
    $stmtFile = mysqli_prepare($conn, $fileQuery);
    mysqli_stmt_bind_param($stmtFile, "s", $file_id);
    mysqli_stmt_execute($stmtFile);
    $resultFile = mysqli_stmt_get_result($stmtFile);

    if ($rowFile = mysqli_fetch_assoc($resultFile)) {
        // Display file details
        echo "<h2>File Details</h2>";
        echo "File ID: " . $rowFile['file_id'] . "<br>";
        echo "File Path: " . $rowFile['file_path'] . "<br>";
        echo "Student Email: " . $rowFile['student_email'] . "<br>";
        // Add any other file details you want to display

        // Display PDF file
        echo "<h3>PDF File</h3>";
        echo "<embed src='" . $rowFile['file_path'] . "' type='application/pdf' width='100%' height='600px'>";
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
