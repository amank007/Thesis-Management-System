<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a student
if (isset($_SESSION['role']) && $_SESSION['role'] == 'student') {
    // Get student email (or any other unique identifier)
    $student_email = $_SESSION['email']; // Assuming student email is unique

    // Fetch student information
    $studentInfoQuery = "SELECT first_name, last_name FROM students WHERE email = ?";
    $stmtStudent = mysqli_prepare($conn, $studentInfoQuery);
    mysqli_stmt_bind_param($stmtStudent, "s", $student_email);
    mysqli_stmt_execute($stmtStudent);
    $resultStudent = mysqli_stmt_get_result($stmtStudent);

    // Get student name
    $rowStudent = mysqli_fetch_assoc($resultStudent);
    $student_first_name = $rowStudent['first_name'];
    $student_last_name = $rowStudent['last_name'];

    // Fetch all files uploaded by the student
    $filesQuery = "SELECT DISTINCT uploads.file_id, uploads.file_path, file_type.type
                   FROM uploads
                   INNER JOIN file_type ON uploads.file_type_id = file_type.type_id
                   WHERE uploads.student_email = ?";

    // Use prepared statement to prevent SQL injection
    $stmtFiles = mysqli_prepare($conn, $filesQuery);
    mysqli_stmt_bind_param($stmtFiles, "s", $student_email);
    mysqli_stmt_execute($stmtFiles);
    $resultFiles = mysqli_stmt_get_result($stmtFiles);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SAC Timeline</title>
        <style>
            body {
                font-family: "Arial", sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
            }

            .navbar {
                background-color: #333;
                color: white;
                padding: 10px;
                width: 100%;
                box-sizing: border-box;
                text-align: center;
            }

            .timeline-item {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                width: 80%;
                max-width: 800px;
                margin-top: 20px;
                padding: 16px;
                animation: fadeIn 0.5s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            .status-item, .review-item {
                background-color: #f8f8f8;
                border-radius: 8px;
                margin-top: 10px;
                padding: 10px;
            }

            .status-item p, .review-item p {
                margin: 0;
                color: #333;
            }

            .status-item p {
                font-weight: bold;
            }

            .review-item p {
                font-style: italic;
            }

            a {
                color: #007bff;
                text-decoration: none;
                font-weight: bold;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <?php include 'student_navbar.php'; ?>
        </div>

        <?php
        // Display SAC timeline for each file
        while ($rowFile = mysqli_fetch_assoc($resultFiles)) {
            echo "<div class='timeline-item'>";
            echo "<h3>File ID: " . $rowFile['file_id'] . "</h3>";
            echo "<p>File Path: <a href='../" . $rowFile['file_path'] . "' target='_blank'>" . $rowFile['file_path'] . "</a></p>"; // Link to the file in the 'uploads' folder
            echo "<p>Type: " . $rowFile['type'] . "</p>";

            // Fetch related status items for each file
            $timelineQuery = "SELECT file_status.status, file_status.timestamp_status, faculty.first_name, faculty.last_name
                              FROM file_status
                              INNER JOIN faculty ON file_status.by_email = faculty.email
                              WHERE file_status.file_id = ?
                              ORDER BY file_status.timestamp_status";

            $stmtTimeline = mysqli_prepare($conn, $timelineQuery);
            mysqli_stmt_bind_param($stmtTimeline, "s", $rowFile['file_id']);
            mysqli_stmt_execute($stmtTimeline);
            $resultTimeline = mysqli_stmt_get_result($stmtTimeline);

            // Display status information for each file
            while ($row = mysqli_fetch_assoc($resultTimeline)) {
                echo "<div class='status-item'>";
                echo "<p>Status: " . $row['status'] . "</p>";
                echo "<p>Updated by: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                echo "<p>Timestamp: " . $row['timestamp_status'] . "</p>";
                echo "</div>";
            }

            // Fetch reviews for each file
            $reviewsQuery = "SELECT reviews.review, reviews.review_timestamp, faculty.first_name, faculty.last_name
                             FROM reviews
                             INNER JOIN faculty ON reviews.reviewer_email = faculty.email
                             WHERE reviews.file_id = ?
                             ORDER BY reviews.review_timestamp";

            $stmtReviews = mysqli_prepare($conn, $reviewsQuery);
            mysqli_stmt_bind_param($stmtReviews, "s", $rowFile['file_id']);
            mysqli_stmt_execute($stmtReviews);
            $resultReviews = mysqli_stmt_get_result($stmtReviews);

            // Display reviews for each file
            while ($review = mysqli_fetch_assoc($resultReviews)) {
                echo "<div class='review-item'>";
                echo "<p>Review: " . $review['review'] . "</p>";
                echo "<p>Reviewer: " . $review['first_name'] . " " . $review['last_name'] . "</p>";
                echo "<p>Timestamp: " . $review['review_timestamp'] . "</p>";
                echo "</div>";
            }

            echo "</div>";
        }

        // Close the statements
        mysqli_stmt_close($stmtFiles);
        mysqli_stmt_close($stmtStudent);
        ?>

    </body>
    </html>

<?php
} else {
    // Redirect if not logged in as a student
    header("Location: login.php");
    exit();
}
?>
