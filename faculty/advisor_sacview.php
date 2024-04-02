<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAC Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa; /* Light Gray */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .sac-item {
            background-color: #ffffff; /* White */
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden; /* Ensure content doesn't overflow */
            transition: all 0.3s ease-in-out; /* Add smooth transition */
        }

        .sac-item:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h3 {
            color: #4caf50; /* Green */
            cursor: pointer;
            text-decoration: underline;
            margin-bottom: 10px;
        }

        .sac-info {
            display: none;
            overflow: hidden; /* Ensure content doesn't overflow */
            transition: max-height 0.3s ease-in-out; /* Add smooth transition */
        }

        .sac-item.active .sac-info {
            display: block;
            max-height: 1000px; /* Set a sufficiently large value */
        }

        p {
            margin: 8px 0;
        }

        .faculty-name-toggle {
            cursor: pointer;
            text-decoration: underline;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #343a40; /* Dark Gray */
            color: white;
            padding: 10px;
            text-align: center;
            z-index: 1000; /* Adjust z-index as needed */
        }
        .sac-container {
            margin-top: 60px; /* Adjust margin-top to leave space for the fixed navbar */
        }


    </style>
</head>

<body>
<div class="navbar">
        <?php include 'faculty_navbar.php'; ?>
    </div>


    <div class="sac-container">
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

        // Fetch SAC information where the faculty is chosen as an advisor
        $sacQuery = "SELECT sac.*, 
                            students.first_name AS student_first_name, students.last_name AS student_last_name,
                            faculty_chair.first_name AS chair_first_name, faculty_chair.last_name AS chair_last_name,
                            faculty_co.first_name AS co_first_name, faculty_co.last_name AS co_last_name,
                            faculty_member1.first_name AS member1_first_name, faculty_member1.last_name AS member1_last_name,
                            faculty_member2.first_name AS member2_first_name, faculty_member2.last_name AS member2_last_name
                     FROM sac
                     INNER JOIN students ON sac.student_email = students.email
                     INNER JOIN faculty AS faculty_chair ON sac.chairperson_email = faculty_chair.email
                     INNER JOIN faculty AS faculty_co ON sac.co_advisor_email = faculty_co.email
                     INNER JOIN faculty AS faculty_member1 ON sac.member1_email = faculty_member1.email
                     INNER JOIN faculty AS faculty_member2 ON sac.member2_email = faculty_member2.email
                     WHERE sac.advisor_email = ?";

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, $sacQuery);
        mysqli_stmt_bind_param($stmt, "s", $faculty_email);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result set
        $result = mysqli_stmt_get_result($stmt);

        // Display SAC information
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='sac-item'>";
            echo "<h3 class='faculty-name-toggle'>" . $faculty_first_name . " " . $faculty_last_name . " - " . $row['student_first_name'] . " " . $row['student_last_name'] . "</h3>";
            echo "<div class='sac-info'>";
            echo "<p>Chairperson: " . $row['chair_first_name'] . " " . $row['chair_last_name'] . "</p>";
            echo "<p>Co-Advisor: " . $row['co_first_name'] . " " . $row['co_last_name'] . "</p>";
            echo "<p>Member 1: " . $row['member1_first_name'] . " " . $row['member1_last_name'] . "</p>";
            echo "<p>Member 2: " . $row['member2_first_name'] . " " . $row['member2_last_name'] . "</p>";
            echo "<p>Requested Timestamp: " . $row['requested_timestamp'] . "</p>";
            echo "<p>Approval Timestamp: " . $row['approval_timestamp'] . "</p>";
            echo "<p>Status: " . $row['status'] . "</p>";
            echo "</div>";
            echo "</div>";
        }

        // Close the statements
        mysqli_stmt_close($stmtFaculty);
    } else {
        // Redirect if not logged in as a faculty
        header("Location: login.php");
        exit();
    }
    ?>
</div>
    <!-- Add JavaScript for toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to each faculty name toggle
            var facultyToggles = document.querySelectorAll('.faculty-name-toggle');

            facultyToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    // Toggle the 'active' class on the parent (.sac-item)
                    var sacItem = this.closest('.sac-item');
                    sacItem.classList.toggle('active');
                });
            });
        });
    </script>

</body>

</html>
