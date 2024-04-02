<?php
// Include your database connection code
include_once('../config.php');

// Start session
session_start();
include('faculty_navbar.php');
// Check if the user is logged in as a faculty member
if (isset($_SESSION['role']) && $_SESSION['role'] == 'faculty') {
    $faculty_email = $_SESSION['email'];

    // Query to get students who have chosen the faculty as an advisor
    $advisor_query = "SELECT student_email FROM sac WHERE advisor_email = ?";
    $advisor_stmt = mysqli_prepare($conn, $advisor_query);
    mysqli_stmt_bind_param($advisor_stmt, "s", $faculty_email);
    mysqli_stmt_execute($advisor_stmt);
    $advisor_result = mysqli_stmt_get_result($advisor_stmt);

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Faculty Files</title>
        <!-- Add your CSS styles or include a stylesheet if needed -->
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

            .student-card {
                background-color: #fff; /* White */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin: 20px;
                border-radius: 8px;
                width: 70%; /* Adjust the width as needed */
                transition: transform 0.3s ease-in-out;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .student-card:hover {
                transform: scale(1.05);
            }

            .file-info {
                margin-top: 10px;
                text-align: center;
            }

            .file-info p {
                margin: 5px 0;
            }

            .file-actions {
                display: flex;
                gap: 10px;
                margin-top: 10px;
                justify-content: center;
            }

            .file-actions button {
                background-color: #3498db; /* Dodger Blue */
                color: white;
                padding: 8px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease-in-out;
            }

            .file-actions button:hover {
                background-color: #2980b9; /* Darker Dodger Blue */
            }
        </style>
    </head>
    <body>
        <?php include 'navbar.php'; ?>

        <?php
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
            echo "<div class='student-card'>";
            echo "<h2>Student Email: $student_email</h2>";

            // Display details of uploaded files for the student
            while ($file_row = mysqli_fetch_assoc($file_result)) {
                $file_id = $file_row['file_id'];
                $file_path = $file_row['file_path'];
                $file_type_id = $file_row['file_type_id'];
                $timestamp_status = $file_row['timestamp_status'];

                echo "<div class='file-info'>";
                echo "<p>File ID: $file_id</p>";
                echo "<p>File Path: $file_path</p>";
                echo "<p>File Type ID: $file_type_id</p>";
                echo "<p>Timestamp: $timestamp_status</p>";
                echo '<a href="view_file.php?file_id=' . $file_id . '" target="_blank">Open File</a>';
                echo "</div>";

                // Add buttons
                echo '<div class="file-actions">';
                echo '<form method="post" action="file_actions.php">';
                echo '<input type="hidden" name="file_id" value="' . $file_id . '">';
                echo '<button type="submit" name="mark_as_read">Mark as Read</button>';
                echo '<button type="submit" name="decline">Decline</button>';
                echo '<button type="submit" name="forward_to_dean">Forward to Dean</button>';
                echo '</form>';
                echo '</div>';
            }

            echo "</div>";

            // Close the file statement
            mysqli_stmt_close($file_stmt);
        }

        // Close the advisor statement
        mysqli_stmt_close($advisor_stmt);

        ?>
    </body>
    </html>

    <?php
} else {
    // Redirect if not logged in as a faculty member
    header("Location: login.php");
    exit();
}
?>
