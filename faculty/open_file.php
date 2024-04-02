<?php
// Include your database connection code
include_once('../config.php');

// Start session
session_start();

// Check if the file_id is set in the URL
if (isset($_GET['file_id'])) {
    $file_id = htmlspecialchars($_GET['file_id']);
    
    // Query to check if the faculty is an advisor for the student who uploaded the file
    $faculty_email = $_SESSION['email'];
    $advisor_query = "SELECT sac.student_email
                     FROM sac
                     INNER JOIN uploads ON sac.student_email = uploads.student_email
                     WHERE sac.advisor_email = ? AND uploads.file_id = ?";
    $advisor_stmt = mysqli_prepare($conn, $advisor_query);
    mysqli_stmt_bind_param($advisor_stmt, "si", $faculty_email, $file_id);
    mysqli_stmt_execute($advisor_stmt);
    $advisor_result = mysqli_stmt_get_result($advisor_stmt);

    if (mysqli_num_rows($advisor_result) > 0) {
        // Fetch file details
        $file_query = "SELECT file_path FROM uploads WHERE file_id = ?";
        $file_stmt = mysqli_prepare($conn, $file_query);
        mysqli_stmt_bind_param($file_stmt, "i", $file_id);
        mysqli_stmt_execute($file_stmt);
        $file_result = mysqli_stmt_get_result($file_stmt);

        if ($file_row = mysqli_fetch_assoc($file_result)) {
            $file_path = $file_row['file_path'];

            // Set headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: inline; filename="' . basename($file_path) . '"');

            // Read the file and output to the browser
            readfile('../' . $file_path);
        } else {
            // File not found
            echo "File not found.";
        }

        // Close the file statement
        mysqli_stmt_close($file_stmt);
    } else {
        // Faculty is not an advisor for the student who uploaded the file
        echo "Unauthorized access.";
    }

    // Close the advisor statement
    mysqli_stmt_close($advisor_stmt);

} else {
    // File ID not provided in the URL
    echo "File ID not provided.";
}
?>
