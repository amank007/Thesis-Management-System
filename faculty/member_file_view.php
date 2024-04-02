<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

include ('./faculty_navbar.php');

// Check if the user is logged in as a SAC member (replace 'sac_member' with your actual role name)
if (isset($_SESSION['role']) && $_SESSION['role'] == 'faculty') {
    // Fetch files with status 'forwardedtomember' and by_email as the current user's email
    $filesQuery = "SELECT uploads.file_id, uploads.file_path, uploads.student_email, 
                           file_status.timestamp_status
                   FROM uploads
                   INNER JOIN file_status ON uploads.file_id = file_status.file_id
                   WHERE file_status.status = 'forwardedtomembers'
                   AND file_status.by_email = ?";

    // Use prepared statement to prevent SQL injection
    $stmtFiles = mysqli_prepare($conn, $filesQuery);
    mysqli_stmt_bind_param($stmtFiles, "s", $_SESSION['email']);
    mysqli_stmt_execute($stmtFiles);
    $resultFiles = mysqli_stmt_get_result($stmtFiles);

    // Display files with status 'forwardedtomember'
    while ($rowFiles = mysqli_fetch_assoc($resultFiles)) {
        echo "<div class='file-card'>";
        echo "<p><strong>File ID:</strong> " . $rowFiles['file_id'] . "</p>";
        echo "<p><strong>File Path:</strong> " . $rowFiles['file_path'] . "</p>";
        echo "<p><strong>Student Email:</strong> " . $rowFiles['student_email'] . "</p>";
        echo "<p><strong>Timestamp:</strong> " . $rowFiles['timestamp_status'] . "</p>";
        echo "<div class='file-buttons'>";
        echo "<button onclick='viewFile(" . $rowFiles['file_id'] . ")'>View File</button>";
        echo "<button onclick='writeReview(" . $rowFiles['file_id'] . ")'>Write Review</button>";
        echo "</div>";
        echo "</div>";
    }

    // Close the statement
    mysqli_stmt_close($stmtFiles);
} else {
    // Redirect if not logged in as a SAC member
    header("Location: login.php");
    exit();
}
?>

<script>
    function viewFile(fileId) {
        // Add logic to view the file, e.g., redirect to a page with file details
        console.log("Viewing file ID: " + fileId);
        // You can replace the following line with your actual logic to view the file
        window.location.href = "view_file.php?file_id=" + fileId;
    }

    function writeReview(fileId) {
        // Add logic to navigate to a page for writing a review for the specified file
        console.log("Writing review for file ID: " + fileId);
        // You can replace the following line with your actual logic to navigate to the review page
        window.location.href = "write_review.php?file_id=" + fileId;
    }
</script>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f5e9; /* Light Green */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .navbar {
        background-color: #333;
        padding: 15px;
        color: white;
        text-align: center;
        width: 100%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .file-card {
        background-color: #fff; /* White */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 20px;
        width: 70%; /* Adjust the width as needed */
        transition: box-shadow 0.3s ease-in-out;
        text-align: center;
    }

    .file-buttons {
        margin-top: 15px;
    }

    button {
        padding: 10px 15px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 0 10px;
        transition: background-color 0.3s ease-in-out;
    }

    button:hover {
        background-color: #468fa0; /* Teal */
        color: white;
    }
</style>
