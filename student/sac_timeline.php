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


            .spiral-box {
                margin-top: 50px;
                display: flex;
                flex-wrap: wrap;
                max-width: 1200px;
            }
            .spiral-card {
                overflow: hidden;
                border-radius: 8px;
                display: flex;
                flex-wrap: wrap;
                max-width: 300px;
                margin: 10px;
                background: linear-gradient(45deg, #4CAF50, #007BFF);
                color: #fff;
                padding: 16px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                transition: transform 0.5s ease-in-out;
                cursor: pointer;
            }

            .spiral-card:hover {
                transform: scale(1.1);
            }

            .spiral-content {
                display: none;
                margin-top: 10px;
                padding: 10px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                color: #333;
            }

            .spiral-card.active .spiral-content {
                display: block;
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
            <?php include 'student_navbar.php'; ?>

        <?php
        // Display SAC timeline for each file
        
        echo "<div class='spiral-box'>";
        while ($rowFile = mysqli_fetch_assoc($resultFiles)) {
            echo "<div class='spiral-card' onclick='toggleSpiralContent(this)' margin=5px>";
            echo "<h3>File ID: " . $rowFile['file_id'] . "</h3>";
            echo "<p>File Name: " . basename($rowFile['file_path']) . "</p>"; // Displaying only the file name
            echo "<p>Type: " . $rowFile['type'] . "</p>";
            echo "<div class='spiral-content'>";
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

            // Add additional information here, for example:
        
            echo "</div>";
            echo "</div>";
        }
        
        echo "</div>";
        // ... (remaining PHP code)
        ?>

        <script>
            function toggleSpiralContent(card) {
                card.classList.toggle('active');
            }
        </script>
    </body>
    </html>

<?php
} else {
    // Redirect if not logged in as a student
    header("Location: login.php");
    exit();
}
?>
