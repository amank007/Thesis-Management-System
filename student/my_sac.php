<?php
include '../config.php';
session_start();

// Check if the student is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

$studentEmail = $_SESSION["email"];
$sacDetails = getSACDetails($conn, $studentEmail);

function getSACDetails($conn, $studentEmail)
{
    $query = "SELECT sac.*, students.first_name AS student_first_name, students.last_name AS student_last_name,
                      faculty.first_name AS faculty_first_name, faculty.last_name AS faculty_last_name,
                      chairperson.first_name AS chairperson_first_name, chairperson.last_name AS chairperson_last_name,
                      co_advisor.first_name AS co_advisor_first_name, co_advisor.last_name AS co_advisor_last_name,
                      member1.first_name AS member1_first_name, member1.last_name AS member1_last_name,
                      member2.first_name AS member2_first_name, member2.last_name AS member2_last_name
              FROM sac
              LEFT JOIN students ON sac.student_email = students.email
              LEFT JOIN faculty ON sac.advisor_email = faculty.email
              LEFT JOIN faculty AS chairperson ON sac.chairperson_email = chairperson.email
              LEFT JOIN faculty AS co_advisor ON sac.co_advisor_email = co_advisor.email
              LEFT JOIN faculty AS member1 ON sac.member1_email = member1.email
              LEFT JOIN faculty AS member2 ON sac.member2_email = member2.email
              WHERE sac.student_email = '$studentEmail'";

    $result = $conn->query($query);

    if ($result === false) {
        // Add error handling here
        die("Error executing the query: " . $conn->error);
    }

    return $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My SAC</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            width: 100%;
        }

        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            display: inline-block;
        }

        .navbar-right {
            float: right;
        }

        h2 {
            color: #333;
            margin-top: 20px; /* Added margin to separate navbar and content */
        }

        p {
            color: #555;
            margin: 5px 0;
        }

        strong {
            color: #333;
        }

        .status-pending {
            color: #007bff;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php include 'student_navbar.php'; ?>
    </div>

    <div>
        <h2>My SAC Details</h2>

        <?php if (!$sacDetails): ?>
            <p>No SAC details found. Please submit the SAC form.</p>
        <?php else: ?>
            <p><strong>Student Name:</strong> <?php echo $sacDetails['student_first_name'] . ' ' . $sacDetails['student_last_name']; ?></p>
            <p><strong>Advisor:</strong> <?php echo $sacDetails['faculty_first_name'] . ' ' . $sacDetails['faculty_last_name']; ?></p>
            <p><strong>Chairperson:</strong> <?php echo $sacDetails['chairperson_first_name'] . ' ' . $sacDetails['chairperson_last_name']; ?></p>
            <p><strong>Co-Advisor:</strong> <?php echo $sacDetails['co_advisor_first_name'] . ' ' . $sacDetails['co_advisor_last_name']; ?></p>
            <p><strong>Member 1:</strong> <?php echo $sacDetails['member1_first_name'] . ' ' . $sacDetails['member1_last_name']; ?></p>
            <p><strong>Member 2:</strong> <?php echo $sacDetails['member2_first_name'] . ' ' . $sacDetails['member2_last_name']; ?></p>
            <p><strong>Status:</strong> 
                <?php 
                    $statusClass = '';
                    switch ($sacDetails['status']) {
                        case 'pending':
                            $statusClass = 'status-pending';
                            break;
                        case 'approved':
                            $statusClass = 'status-approved';
                            break;
                        case 'rejected':
                            $statusClass = 'status-rejected';
                            break;
                        default:
                            $statusClass = '';
                    }
                    echo '<span class="' . $statusClass . '">' . $sacDetails['status'] . '</span>'; 
                ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
