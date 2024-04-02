<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a faculty member
if (isset($_SESSION['role']) && $_SESSION['role'] == 'faculty') {
    // Get faculty email (or any other unique identifier)
    $faculty_email = $_SESSION['email']; // Assuming faculty email is unique

    // Fetch faculty information
    $facultyInfoQuery = "SELECT first_name, last_name FROM faculty WHERE email = ?";
    $stmtFaculty = mysqli_prepare($conn, $facultyInfoQuery);
    mysqli_stmt_bind_param($stmtFaculty, "s", $faculty_email);
    mysqli_stmt_execute($stmtFaculty);
    $resultFaculty = mysqli_stmt_get_result($stmtFaculty);

    // Get faculty name
    $rowFaculty = mysqli_fetch_assoc($resultFaculty);
    $faculty_first_name = $rowFaculty['first_name'];
    $faculty_last_name = $rowFaculty['last_name'];

    // Fetch SAC information where the faculty is chosen as Member 1 or Member 2
    $sacQuery = "SELECT sac.*, 
                    students.first_name AS student_first_name, students.last_name AS student_last_name,
                    faculty_chair.first_name AS chair_first_name, faculty_chair.last_name AS chair_last_name,
                    faculty_advisor.first_name AS advisor_first_name, faculty_advisor.last_name AS advisor_last_name,
                    faculty_co.first_name AS co_first_name, faculty_co.last_name AS co_last_name,
                    faculty_member1.first_name AS member1_first_name, faculty_member1.last_name AS member1_last_name,
                    faculty_member2.first_name AS member2_first_name, faculty_member2.last_name AS member2_last_name
             FROM sac
             INNER JOIN students ON sac.student_email = students.email
             INNER JOIN faculty AS faculty_chair ON sac.chairperson_email = faculty_chair.email
             INNER JOIN faculty AS faculty_advisor ON sac.advisor_email = faculty_advisor.email
             INNER JOIN faculty AS faculty_co ON sac.co_advisor_email = faculty_co.email
             INNER JOIN faculty AS faculty_member1 ON sac.member1_email = faculty_member1.email
             INNER JOIN faculty AS faculty_member2 ON sac.member2_email = faculty_member2.email
             WHERE sac.member1_email = ? OR sac.member2_email = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sacQuery);
    mysqli_stmt_bind_param($stmt, "ss", $faculty_email, $faculty_email);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SAC Information</title>
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

            .sac-item {
                background-color: #fff; /* White */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin: 20px;
                border-radius: 8px;
                width: 70%; /* Adjust the width as needed */
                transition: transform 0.3s ease-in-out;
            }

            .sac-item:hover {
                transform: scale(1.05);
            }

            .faculty-name-toggle {
                cursor: pointer;
                text-decoration: underline;
            }

            .sac-info {
                display: none;
            }

            .sac-item:hover .sac-info {
                display: block;
            }

            .sac-info p {
                margin: 5px 0;
            }
        </style>
    </head>
    <body>
        <?php include 'faculty_navbar.php'; ?>
        <h2>SAC Information</h2>
        <?php
        // Display SAC information
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class='sac-item'>
                <h3 class='faculty-name-toggle'><?php echo $faculty_first_name . " " . $faculty_last_name . " - " . $row['student_first_name'] . " " . $row['student_last_name']; ?></h3>
                <div class='sac-info'>
                    <p>Chairperson: <?php echo $row['chair_first_name'] . " " . $row['chair_last_name']; ?></p>
                    <p>Advisor: <?php echo $row['advisor_first_name'] . " " . $row['advisor_last_name']; ?></p>
                    <p>Co-Advisor: <?php echo $row['co_first_name'] . " " . $row['co_last_name']; ?></p>
                    <p>Member 1: <?php echo $row['member1_first_name'] . " " . $row['member1_last_name']; ?></p>
                    <p>Member 2: <?php echo $row['member2_first_name'] . " " . $row['member2_last_name']; ?></p>
                    <p>Requested Timestamp: <?php echo $row['requested_timestamp']; ?></p>
                    <p>Approval Timestamp: <?php echo $row['approval_timestamp']; ?></p>
                    <p>Status: <?php echo $row['status']; ?></p>
                </div>
            </div>
            <?php
        }

        // Close the statements
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtFaculty);

    } else {
        // Redirect if not logged in as a faculty
        header("Location: ../login.php");
        exit();
    }
    ?>

    <!-- Add JavaScript for toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to each faculty name toggle
            var facultyToggles = document.querySelectorAll('.faculty-name-toggle');

            facultyToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    // Toggle the 'hidden' class on the next sibling element (sac-info)
                    var sacInfo = this.nextElementSibling;
                    sacInfo.classList.toggle('hidden');
                });
            });
        });
    </script>
    </body>
    </html>
