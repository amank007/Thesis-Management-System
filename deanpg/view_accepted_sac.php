<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a dean
if (isset($_SESSION['role']) && $_SESSION['role'] == 'deanpg') {
    // Fetch all accepted SAC information, ordered by approval timestamp (newest first)
    $sacQuery = "SELECT sac.*, 
    students.first_name AS student_first_name, students.last_name AS student_last_name,
    faculty_advisor.first_name AS advisor_first_name, faculty_advisor.last_name AS advisor_last_name,
    faculty_co.first_name AS co_first_name, faculty_co.last_name AS co_last_name,
    faculty_chair.first_name AS chair_first_name, faculty_chair.last_name AS chair_last_name,
    faculty_member1.first_name AS member1_first_name, faculty_member1.last_name AS member1_last_name,
    faculty_member2.first_name AS member2_first_name, faculty_member2.last_name AS member2_last_name
    FROM sac
    INNER JOIN students ON sac.student_email = students.email
    INNER JOIN faculty AS faculty_advisor ON sac.advisor_email = faculty_advisor.email
    LEFT JOIN faculty AS faculty_co ON sac.co_advisor_email = faculty_co.email
    LEFT JOIN faculty AS faculty_chair ON sac.chairperson_email = faculty_chair.email
    LEFT JOIN faculty AS faculty_member1 ON sac.member1_email = faculty_member1.email
    LEFT JOIN faculty AS faculty_member2 ON sac.member2_email = faculty_member2.email
    WHERE sac.status = 'Accepted'
    ORDER BY sac.approval_timestamp DESC";

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sacQuery);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect if not logged in as a dean
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeanPG Dashboard</title>
    <!-- Add your CSS styles or include a stylesheet if needed -->
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .navbar {
            background-color: #343a40; /* Dark Gray */
            overflow: hidden;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .navbar a {
            float: left;
            display: block;
            color: #ffffff; /* White */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .navbar a:hover {
            background-color: #6c757d; /* Gray */
            color: #ffffff; /* White */
        }

        .navbar-right {
            float: right;
        }

        .navbar-right p {
            color: #ffffff; /* White */
            display: inline-block;
            margin: 0;
            padding: 14px 16px;
            transition: color 0.3s ease-in-out;
        }

        .navbar-right a {
            color: #ffffff; /* White */
            transition: color 0.3s ease-in-out;
        }

        .navbar-right a:hover {
            color: #d4d8db; /* Light Gray */
        }

        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
            text-align: center;
        }

        .dashboard-button {
            background-color: #4caf50;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .dashboard-button:hover {
            background-color: #45a049;
        }

        .icon {
            margin-right: 10px;
        }

        .sac-item {
            margin-bottom: 20px;
            background-color: #ffffff; /* White */
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 5px;
            padding: 20px;
            width: 300px;
            transition: transform 0.3s ease-in-out;
        }

        .sac-item:hover {
            transform: scale(1.05);
        }

        .sac-toggle {
            cursor: pointer;
            text-decoration: underline;
            padding: 16px;
            margin: 0;
            background-color: #f8f9fa; /* Light Gray */
            transition: background-color 0.3s ease-in-out;
        }

        .sac-toggle:hover {
            background-color: #e9ecef; /* Lighter Gray */
        }

        .sac-info.hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'deanpg_navbar.php'; ?>

   

    <div class="sac-container">
        <?php
        // Display accepted SAC information
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='sac-item'>";
            echo "<h3 class='sac-toggle'>" . $row['student_first_name'] . " " . $row['student_last_name'] . " - " . $row['advisor_first_name'] . " " . $row['advisor_last_name'] . " - " . $row['approval_timestamp'] . "</h3>";
            echo "<div class='sac-info hidden'>";
            echo "<p>Co-Advisor: " . $row['co_first_name'] . " " . $row['co_last_name'] . "</p>";
            echo "<p>Chairperson: " . $row['chair_first_name'] . " " . $row['chair_last_name'] . "</p>";
            echo "<p>Member 1: " . $row['member1_first_name'] . " " . $row['member1_last_name'] . "</p>";
            echo "<p>Member 2: " . $row['member2_first_name'] . " " . $row['member2_last_name'] . "</p>";
            echo "<p>Requested Timestamp: " . $row['requested_timestamp'] . "</p>";
            echo "<p>Approval Timestamp: " . $row['approval_timestamp'] . "</p>";
            // Add more information as needed
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Add JavaScript for toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to each SAC toggle
            var sacToggles = document.querySelectorAll('.sac-toggle');

            sacToggles.forEach(function (toggle) {
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
