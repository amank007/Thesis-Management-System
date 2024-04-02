<?php
include '../config.php';
session_start();

// Check if the faculty is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

$facultyEmail = $_SESSION["email"];
$requests = fetchSACRequests($conn, $facultyEmail);

function fetchSACRequests($conn, $facultyEmail)
{
    $query = "SELECT sac.*, students.email AS student_email, students.first_name AS student_username, 
                      faculty.email AS faculty_email, faculty.first_name AS faculty_username
              FROM sac
              LEFT JOIN students ON sac.student_email = students.email
              LEFT JOIN faculty ON sac.advisor_email = faculty.email
              WHERE sac.advisor_email = '$facultyEmail' AND sac.status = 'pending'";
              
    $result = $conn->query($query);

    if ($result === false) {
        // Add error handling here
        die("Error executing the query: " . $conn->error);
    }

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }

    return $requests;
}

function getSACMembers($conn, $requestId)
{
    // Reopen the connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT chairperson_email, co_advisor_email, member1_email, member2_email FROM sac WHERE student_email = '$requestId'";
    $result = $conn->query($query);

    if ($result === false) {
        return [];
    }

    // Fetch data
    $data = $result->fetch_assoc();

    // Close the connection
    $conn->close();

    return $data;
}

function updateSACStatus($conn, $studentEmail, $status)
{
    $studentEmail = filter_var($studentEmail, FILTER_VALIDATE_EMAIL);

    if ($studentEmail === false) {
        return false;
    }

    $status = mysqli_real_escape_string($conn, $status);

    $updateQuery = "UPDATE sac SET status = '$status', approval_timestamp = CURRENT_TIMESTAMP WHERE student_email = '$studentEmail'";
    return $conn->query($updateQuery);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentEmail = mysqli_real_escape_string($conn, $_POST["request_id"]);
    $action = mysqli_real_escape_string($conn, $_POST["action"]);

    if ($action === "accept") {
        if (updateSACStatus($conn, $studentEmail, "accepted")) {
            // Successful update
            header("Location: requested_sac.php");
            exit();
        } else {
            // Error updating status
            echo "Error accepting request.";
        }
    } elseif ($action === "reject") {
        if (updateSACStatus($conn, $studentEmail, "rejected")) {
            // Successful update
            header("Location: requested_sac.php");
            exit();
        } else {
            // Error updating status
            echo "Error rejecting request.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAC Requests</title>

    <!-- Add your CSS styles or include a stylesheet if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f5e9; /* Light Green */
        }

        .navbar {
            background-color: #468fa0; /* Teal */
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #72acb4; /* Light Teal */
            color: black;
        }

        .h2-container {
            text-align: center;
            margin: 20px;
        }

        h2 {
            color: #333; /* Dark Gray */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Light Gray */
        }

        th {
            background-color: #468fa0; /* Teal */
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5; /* Light Gray */
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button.accept {
            background-color: #77ab59; /* Olive Green */
            color: white;
        }

        button.accept:hover {
            background-color: #6c9b4b; /* Darker Olive Green */
        }

        button.reject {
            background-color: #d9534f; /* Red */
            color: white;
        }

        button.reject:hover {
            background-color: #c9302c; /* Darker Red */
        }
    </style>
</head>

<body>
    <?php include 'faculty_navbar.php'; ?>

    <div class="h2-container">
        <h2>SAC Requests</h2>
    </div>

    <?php if (empty($requests)): ?>
        <p>No pending SAC requests found.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Student Email</th>
                <th>Student Username</th>
                <th>Faculty Email</th>
                <th>Faculty Username</th>
                <th>Chairperson Email</th>
                <th>Co-Advisor Email</th>
                <th>Member 1 Email</th>
                <th>Member 2 Email</th>
                <th>Requested Timestamp</th>
                <th>Action</th>
            </tr>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo $request['student_email']; ?></td>
                    <td><?php echo $request['student_username']; ?></td>
                    <td><?php echo $request['advisor_email']; ?></td>
                    <td><?php echo $request['faculty_username']; ?></td>
                    <?php
                        $members = getSACMembers($conn, $request['student_email']);
                    ?>
                    <td><?php echo $members['chairperson_email']; ?></td>
                    <td><?php echo $members['co_advisor_email']; ?></td>
                    <td><?php echo $members['member1_email']; ?></td>
                    <td><?php echo $members['member2_email']; ?></td>
                    <td><?php echo $request['requested_timestamp']; ?></td>
                    <td class="action-buttons">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="request_id" value="<?php echo $request['student_email']; ?>">
                            <button class="accept" type="submit" name="action" value="accept">Accept</button>
                            <button class="reject" type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>

</html>
