<?php
session_start(); // Start the session

include '../config.php';

$successMessage = "";
$error = "";
$userInfoMessage = "";
$showForm = true;

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

    $checkExistingEmail = "SELECT * FROM faculty WHERE email = '$email'";
    $result = $conn->query($checkExistingEmail);

    if ($result === false) {
        $error = "Error checking existing email: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // User is already registered, display information
            $userInfo = $result->fetch_assoc();
            $userInfoMessage = "You are already registered.<br>
                               Email: {$userInfo['email']}<br>
                               Name: {$userInfo['first_name']} {$userInfo['last_name']}<br>
                               Department: {$departmentOptions[$userInfo['dept_id']]}<br>
                               Specialization: {$userInfo['specialization']}";

            // Set flag to not display the registration form
            $showForm = false;
        }
    }
}

// The rest of your code remains unchanged...

if ($_SERVER["REQUEST_METHOD"] == "POST" && $showForm) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $dept_id = mysqli_real_escape_string($conn, $_POST["department"]);
    $specialization = mysqli_real_escape_string($conn, $_POST["specialization"]);

    // Check if the email is not already registered in the faculty table
    $checkExistingEmail = "SELECT * FROM faculty WHERE email = '$email'";
    $result = $conn->query($checkExistingEmail);

    if ($result === false) {
        $error = "Error checking existing email: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // User is already registered, display information
            $userInfo = $result->fetch_assoc();
            $userInfoMessage = "You are already registered.<br>
                               Email: {$userInfo['email']}<br>
                               Name: {$userInfo['first_name']} {$userInfo['last_name']}<br>
                               Department: {$departmentOptions[$userInfo['dept_id']]}<br>
                               Specialization: {$userInfo['specialization']}";
            $showForm = false;
        } else {
            // User is not registered, proceed with registration
            $insertFacultyQuery = "INSERT INTO faculty (email, first_name, last_name, dept_id, specialization) 
                                   VALUES ('$email', '$first_name', '$last_name', '$dept_id', '$specialization')";

            if ($conn->query($insertFacultyQuery) === FALSE) {
                $error = "Error inserting data into faculty table: " . $conn->error;
            } else {
                $successMessage = "Faculty registration successful for $first_name $last_name with email: $email";
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
    <title>Faculty Registration</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Light Gray */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
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

        .card {
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff; /* White */
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 60px; /* Adjust margin-top to leave space for the fixed navbar */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input, select {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50; /* Green */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker Green */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php include 'faculty_navbar.php'; ?>
    </div>
    <div class="card">
        <?php echo $successMessage; ?>
        <?php echo $error; ?>
        <?php echo $userInfoMessage; ?>

        <?php if ($showForm): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <?php foreach ($departmentOptions as $dept_id => $department_name): ?>
                        <option value="<?php echo $dept_id; ?>"><?php echo $department_name; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" required>

                <input type="submit" value="Register">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
