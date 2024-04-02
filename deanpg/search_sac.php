<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a dean
if (isset($_SESSION['role']) && $_SESSION['role'] == 'deanpg') {
    // Initialize the query with all SAC information with "Accepted" status
    $sacQuery = "SELECT sac.*, 
                    students.first_name AS student_first_name, students.last_name AS student_last_name,
                    faculty_chair.first_name AS chair_first_name, faculty_chair.last_name AS chair_last_name,
                    faculty_advisor.first_name AS advisor_first_name, faculty_advisor.last_name AS advisor_last_name,
                    faculty_co.first_name AS co_first_name, faculty_co.last_name AS co_last_name
                 FROM sac
                 INNER JOIN students ON sac.student_email = students.email
                 INNER JOIN faculty AS faculty_chair ON sac.chairperson_email = faculty_chair.email
                 INNER JOIN faculty AS faculty_advisor ON sac.advisor_email = faculty_advisor.email
                 INNER JOIN faculty AS faculty_co ON sac.co_advisor_email = faculty_co.email
                 WHERE sac.status = 'Accepted'
                 ORDER BY sac.approval_timestamp DESC"; // You may adjust the ordering as needed

    // Check if a search email is provided
    $searchEmail = isset($_GET['search_email']) ? trim($_GET['search_email']) : '';

    // Modify the query if a search email is provided
    if (!empty($searchEmail)) {
        $sacQuery = "SELECT sac.*, 
                        students.first_name AS student_first_name, students.last_name AS student_last_name,
                        faculty_chair.first_name AS chair_first_name, faculty_chair.last_name AS chair_last_name,
                        faculty_advisor.first_name AS advisor_first_name, faculty_advisor.last_name AS advisor_last_name,
                        faculty_co.first_name AS co_first_name, faculty_co.last_name AS co_last_name
                     FROM sac
                     INNER JOIN students ON sac.student_email = students.email
                     INNER JOIN faculty AS faculty_chair ON sac.chairperson_email = faculty_chair.email
                     INNER JOIN faculty AS faculty_advisor ON sac.advisor_email = faculty_advisor.email
                     INNER JOIN faculty AS faculty_co ON sac.co_advisor_email = faculty_co.email
                     WHERE (sac.advisor_email = ? OR sac.student_email = ?) AND sac.status = 'Accepted'
                     ORDER BY sac.approval_timestamp DESC";
    }

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sacQuery);

    // If a search email is provided, bind parameters
    if (!empty($searchEmail)) {
        mysqli_stmt_bind_param($stmt, "ss", $searchEmail, $searchEmail);
    }

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Display SAC information
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='sac-item'>";
        echo "<h3>Student: " . $row['student_first_name'] . " " . $row['student_last_name'] . "</h3>";
        echo "<p>Chairperson: " . $row['chair_first_name'] . " " . $row['chair_last_name'] . "</p>";
        echo "<p>Advisor: " . $row['advisor_first_name'] . " " . $row['advisor_last_name'] . "</p>";
        echo "<p>Co-Advisor: " . $row['co_first_name'] . " " . $row['co_last_name'] . "</p>";
        echo "<p>Requested Timestamp: " . $row['requested_timestamp'] . "</p>";
        echo "<p>Approval Timestamp: " . $row['approval_timestamp'] . "</p>";
        echo "<p>Status: " . $row['status'] . "</p>";
        echo "</div>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect if not logged in as a dean
    header("Location: login.php");
    exit();
}
?>

<!-- Search form -->
<form action="view_all_sac.php" method="GET">
    <label for="search_email">Search by Email:</label>
    <input type="text" name="search_email" id="search_email" placeholder="Enter email">
    <button type="submit">Search</button>
</form>
