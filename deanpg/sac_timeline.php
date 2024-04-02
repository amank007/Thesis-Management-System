<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a deanpg
if (isset($_SESSION['role']) && $_SESSION['role'] == 'deanpg') {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_student'])) {
        // Get student ID from the form submission
        $student_id = $_POST['student_id'];

        // Fetch student information
        $studentInfoQuery = "SELECT first_name, last_name FROM students WHERE student_id = ?";
        $stmtStudent = mysqli_prepare($conn, $studentInfoQuery);
        mysqli_stmt_bind_param($stmtStudent, "s", $student_id);
        mysqli_stmt_execute($stmtStudent);
        $resultStudent = mysqli_stmt_get_result($stmtStudent);

        // Get student name
        $rowStudent = mysqli_fetch_assoc($resultStudent);
        $student_first_name = $rowStudent['first_name'];
        $student_last_name = $rowStudent['last_name'];

        // Fetch SAC timeline for the student
        $timelineQuery = "SELECT uploads.file_id, uploads.file_path, file_type.type, file_status.status, file_status.timestamp_status, faculty.first_name, faculty.last_name
                          FROM uploads
                          INNER JOIN file_status ON uploads.file_id = file_status.file_id
                          INNER JOIN faculty ON file_status.by_email = faculty.email
                          INNER JOIN file_type ON uploads.file_type_id = file_type.type_id
                          WHERE uploads.student_email = ?
                          ORDER BY file_status.timestamp_status";

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $timelineQuery);
        mysqli_stmt_bind_param($stmt, "s", $student_email);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result set
        $result = mysqli_stmt_get_result($stmt);

        // Display SAC timeline
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='timeline-item'>";
            echo "<h3>File ID: " . $row['file_id'] . "</h3>";
            echo "<p>File Path: <a href='../" . $row['file_path'] . "' target='_blank'>" . $row['file_path'] . "</a></p>"; // Link to the file in the 'uploads' folder
            echo "<p>Type: " . $row['type'] . "</p>";
            echo "<p>Status: " . $row['status'] . "</p>";
            echo "<p>Updated by: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
            echo "<p>Timestamp: " . $row['timestamp_status'] . "</p>";
            echo "</div>";
        }

        // Close the statement
        mysqli_stmt_close($stmtStudent);
    }
} else {
    // Redirect if not logged in as a deanpg
    header("Location: login.php");
    exit();
}
?>
