
<?php
include '../config.php';
session_start();
$successMessage = "";
$error = "";
$userInfoMessage = "";
$showForm = true; // Flag to determine whether to display the registration form

// Fetch department options from the departments table
$departmentOptionsQuery = "SELECT department_id, department_name FROM departments";
$departmentOptionsResult = $conn->query($departmentOptionsQuery);

$departmentOptions = [];
while ($row = $departmentOptionsResult->fetch_assoc()) {
    $departmentOptions[$row['department_id']] = $row['department_name'];
}

// Check if the user is already registered
if (isset($_SESSION["email"])) {
    $email = mysqli_real_escape_string($conn, $_SESSION["email"]);

    $checkExistingEmail = "SELECT * FROM students WHERE email = '$email'";

    $result = $conn->query($checkExistingEmail);

    if ($result === false) {
        $error = "Error checking existing email: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // User is already registered, display information
            $userInfo = $result->fetch_assoc();
            $userInfoMessage = "You are already registered.<br>
                               PID: {$userInfo['pid']}<br>
                               Name: {$userInfo['first_name']} {$userInfo['last_name']}<br>
                               Department: {$departmentOptions[$userInfo['dept_id']]}";

            // Set flag to not display the registration form
            $showForm = false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $showForm) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pid = mysqli_real_escape_string($conn, $_POST["pid"]);
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $dept_id = mysqli_real_escape_string($conn, $_POST["department"]);
    // Add other form fields as needed

    $checkExistingEmail = "SELECT * FROM students WHERE email = '$email'";

    $result = $conn->query($checkExistingEmail);

    if ($result === false) {
        $error = "Error checking existing email: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            $userInfo = $result->fetch_assoc();
            $userInfoMessage = "You are already registered.<br>
                               PID: {$userInfo['pid']}<br>
                               Name: {$userInfo['first_name']} {$userInfo['last_name']}<br>
                               Department: {$departmentOptions[$userInfo['dept_id']]}";
            $showForm = false;
        } else {
            $insertStudentQuery = "INSERT INTO students (email, pid, first_name, last_name, dept_id) 
                                   VALUES ('$email', '$pid', '$first_name', '$last_name', '$dept_id')";

            if ($conn->query($insertStudentQuery) === FALSE) {
                $error = "Error inserting data into students table: " . $conn->error;
            } else {
                $successMessage = "Student registration successful for $first_name $last_name with email: $email";
                $showForm = false;
            }
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
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #4CAF50; /* Pastel green */
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-message {
            font-size: 18px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include 'student_navbar.php'; ?>
<div class="container">
    <div class="card">
        <?php echo $successMessage; ?>
        <?php echo $error; ?>
        <?php echo $userInfoMessage; ?>
    </div>

    <?php if ($showForm): ?>
        <!-- Display the registration form only if showForm is true -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="pid">PID:</label>
            <input type="text" id="pid" name="pid" required><br>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <?php
                foreach ($departmentOptions as $dept_id => $department_name) {
                    echo "<option value=\"$dept_id\">$department_name</option>";
                }
                ?>
            </select><br>

            <!-- Add more form fields as needed -->

            <input type="submit" value="Register">
        </form>
    <?php endif; ?>
</div>
</body>
</html>
