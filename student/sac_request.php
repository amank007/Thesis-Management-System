<?php
// Assuming you have established a MySQL connection
include '../config.php';
session_start();
$error = $successMessage = '';

// Function to check the status of the previous SAC request
function getPreviousRequestStatus($conn, $studentEmail) {
    $query = "SELECT status FROM sac WHERE student_email = '$studentEmail' ORDER BY requested_timestamp DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['status'];
    }

    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentEmail = mysqli_real_escape_string($conn, $_SESSION["email"]);

    // Check the status of the previous SAC request
    $previousRequestStatus = getPreviousRequestStatus($conn, $studentEmail);

    // Allow submission only if the status is "rejected" or there is no previous request
    if ($previousRequestStatus === null || $previousRequestStatus === 'rejected') {
        $advisorEmail = mysqli_real_escape_string($conn, $_POST["advisor"]);
        $coAdvisorEmail = mysqli_real_escape_string($conn, $_POST["co_advisor"]);
        $chairpersonEmail = mysqli_real_escape_string($conn, $_POST["chairperson"]);
        $member1Email = mysqli_real_escape_string($conn, $_POST["member1"]);
        $member2Email = mysqli_real_escape_string($conn, $_POST["member2"]);

        if ($previousRequestStatus === 'rejected') {
            // Update the existing rejected request with new values
            $updateSacQuery = "UPDATE sac 
                               SET advisor_email = '$advisorEmail', co_advisor_email = '$coAdvisorEmail', chairperson_email = '$chairpersonEmail', member1_email = '$member1Email', member2_email = '$member2Email', requested_timestamp = NOW(), status = 'pending' 
                               WHERE student_email = '$studentEmail'
                               ORDER BY requested_timestamp DESC
                               LIMIT 1";
        } else {
            // Insert a new request if there is no previous request
            $updateSacQuery = "INSERT INTO sac (student_email, advisor_email, co_advisor_email, chairperson_email, member1_email, member2_email, requested_timestamp, status) 
                               VALUES ('$studentEmail', '$advisorEmail', '$coAdvisorEmail', '$chairpersonEmail', '$member1Email', '$member2Email', NOW(), 'pending')";
        }

        if ($conn->query($updateSacQuery) === FALSE) {
            $error = "Error updating/inserting data into sac table: " . $conn->error;
        } else {
            $successMessage = "SAC request submitted successfully!";
        }
    } else {
        $error = "You cannot submit a new SAC request when the previous request status is '$previousRequestStatus'.";
    }
}

// ... (rest of your code)






// // Function to fetch faculty names of the same department as the student
function getFacultyNamesOfSameDepartment($conn, $studentEmail, $includeNullOption = false, $selectedOption = '') {
    $names = "";

    // Get the dept_id of the logged-in student
    $studentDeptQuery = "SELECT dept_id FROM students WHERE email = '$studentEmail'";
    $studentDeptResult = $conn->query($studentDeptQuery);

    if ($studentDeptResult->num_rows > 0) {
        $studentDeptRow = $studentDeptResult->fetch_assoc();
        $dept_id = $studentDeptRow["dept_id"];

        // Add null option if requested
        if ($includeNullOption) {
            $selected = ($selectedOption === '') ? 'selected' : '';
            $names .= '<option value="" ' . $selected . '>Select...</option>';
        }

        // Fetch faculty names for the same department
        $query = "SELECT email, CONCAT(first_name, ' ', last_name) AS full_name
                  FROM faculty
                  WHERE dept_id = '$dept_id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($row["email"] === $selectedOption) ? 'selected' : '';
                $names .= '<option value="' . $row["email"] . '" ' . $selected . '>' . $row["full_name"] . '</option>';
            }
        } else {
            $names = '<option value="">No faculty names found for the same department.</option>';
        }
    } else {
        $names = '<option value="">Student department information not found.</option>';
    }

    return $names;
}

// Function to get the previously chosen options for Co-Advisor, Chairperson, SAC Member 1, and SAC Member 2
function getPreviousOptions($conn, $studentEmail) {
    $options = array();

    // Fetch the data from the database based on the student's email
    $query = "SELECT co_advisor_email, chairperson_email, member1_email, member2_email
              FROM sac
              WHERE student_email = '$studentEmail' AND status = 'rejected'
              ORDER BY requested_timestamp DESC
              LIMIT 1";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $options['co_advisor'] = $row['co_advisor_email'];
        $options['chairperson'] = $row['chairperson_email'];
        $options['member1'] = $row['member1_email'];
        $options['member2'] = $row['member2_email'];
    }

    return $options;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAC Request</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            justify-content: center;
            align-items: center;
        }

        form {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            margin: 50px 0 0 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            margin-top: 10px;
        }

        .success-message {
            color: #28a745;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'student_navbar.php'; ?>
        <div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php
            $previousOptions = getPreviousOptions($conn, $_SESSION["email"]);
            ?>
        <label for="advisor">Advisor:</label>
        <select id="advisor" name="advisor" required>
            <?php echo getFacultyNamesOfSameDepartment($conn, $_SESSION["email"],false, $previousOptions['advisor']); ?>
        </select><br>

        <label for="co_advisor">Co-Advisor:</label>
        <select id="co_advisor" name="co_advisor" >
            <?php echo getFacultyNamesOfSameDepartment($conn, $_SESSION["email"],false, $previousOptions['co_advisor']); ?>
        </select><br>

        <label for="chairperson">Chairperson:</label>
        <select id="chairperson" name="chairperson" >
            <?php echo getFacultyNamesOfSameDepartment($conn, $_SESSION["email"],false,$previousOptions['chairperson']); ?>
        </select><br>

        <label for="member1">SAC Member 1:</label>
        <select id="member1" name="member1" >
            <?php echo getFacultyNamesOfSameDepartment($conn, $_SESSION["email"],false,$previousOptions['member1']); ?>
        </select><br>

        <label for="member2">SAC Member 2:</label>
        <select id="member2" name="member2" >
            <?php echo getFacultyNamesOfSameDepartment($conn, $_SESSION["email"],false,$previousOptions['member2']); ?>
        </select><br>

        <input type="submit" value="Submit SAC Request">
    </form>
    </div>
    <?php
    // Display success or error messages

    if (!empty($successMessage)) {
        echo '<div class="success-message">' . $successMessage . '</div>';
    } elseif (!empty($error)) {
        echo '<div class="error-message">' . $error . '</div>';
    }
   

    // Close the connection after using it
    $conn->close();
    ?>
</body>
</html>
