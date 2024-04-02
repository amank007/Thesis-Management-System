<?php
// Include your database connection code
include_once('../config.php');

// Start session
session_start();

// Check if the user is logged in as a faculty member
if (isset($_SESSION['role']) && $_SESSION['role'] == 'faculty') {
    $faculty_email = $_SESSION['email'];

    // Query to get students who have chosen the faculty as an advisor
    $advisor_query = "SELECT student_email FROM sac WHERE advisor_email = ?";
    $advisor_stmt = mysqli_prepare($conn, $advisor_query);
    mysqli_stmt_bind_param($advisor_stmt, "s", $faculty_email);
    mysqli_stmt_execute($advisor_stmt);
    $advisor_result = mysqli_stmt_get_result($advisor_stmt);

    // Display details of uploaded files for each student
    while ($advisor_row = mysqli_fetch_assoc($advisor_result)) {
        $student_email = $advisor_row['student_email'];

        // Query to get uploaded files for the student with "uploaded" status
        $file_query = "SELECT uploads.file_id, uploads.file_path, uploads.file_type_id, file_status.timestamp_status
                       FROM uploads
                       INNER JOIN file_status ON uploads.file_id = file_status.file_id
                       WHERE uploads.student_email = ? AND file_status.status = 'uploaded'";
        $file_stmt = mysqli_prepare($conn, $file_query);
        mysqli_stmt_bind_param($file_stmt, "s", $student_email);
        mysqli_stmt_execute($file_stmt);
        $file_result = mysqli_stmt_get_result($file_stmt);

        // Display student details
        echo "<h2>Student Email: $student_email</h2>";

        // Display details of uploaded files for the student
        while ($file_row = mysqli_fetch_assoc($file_result)) {
            $file_id = $file_row['file_id'];
            $file_path = $file_row['file_path'];
            $file_type_id = $file_row['file_type_id'];
            $timestamp_status = $file_row['timestamp_status'];

            echo "File ID: $file_id<br>";
            echo "File Path: $file_path<br>";
            echo "File Type ID: $file_type_id<br>";
            echo "Timestamp: $timestamp_status<br>";
            echo '<a href="open_file.php?file_id=' . $file_id . '" target="_blank">Open File</a><br>';

            // Add buttons
            echo '<form method="post" action="file_actions.php">';
            echo '<input type="hidden" name="file_id" value="' . $file_id . '">';
            echo '<button type="submit" name="mark_as_read">Mark as Read</button>';
            echo '<button type="submit" name="decline">Decline</button>';
            echo '<button type="submit" name="forward_to_dean">Forward to Dean</button>';
            echo '</form>';
            echo '<br>';
        }

        // Close the file statement
        mysqli_stmt_close($file_stmt);
    }

    // Close the advisor statement
    mysqli_stmt_close($advisor_stmt);

} else {
    // Redirect if not logged in as a faculty member
    header("Location: login.php");
    exit();
}
?>
