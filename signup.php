<?php
include './config.php';

$successMessage = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    // Check if the email is not already registered
    $checkExistingEmail = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($checkExistingEmail);

    if ($result === false) {
        $error = "Error checking existing email: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            $error = "Error: Email is already registered.";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add a timestamp column to the "users" table
            $timestamp = date("Y-m-d H:i:s");

            $insertQuery = "INSERT INTO users (email, password, role, signup_timestamp) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $email, $hashedPassword, $role, $timestamp);

            if ($stmt->execute()) {
                $successMessage = "Signup successful as $role with email: $email";
                // Redirect to login.php after a short delay
                header("refresh:2;url=./index.php");
            } else {
                $error = "Error inserting data: " . $stmt->error;
            }

            $stmt->close();
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
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #6B8E23; /* Pastel olive green */
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .role-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Pastel green */
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-message {
            margin-top: 15px;
            color: #555;
        }

        a {
            color: #4CAF50; /* Pastel green */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php echo $successMessage; ?>
<?php echo $error; ?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <div class="role-container">
        <label>Role:</label>
        <input type="radio" id="student" name="role" value="student" checked>
        <label for="student">Student</label>
        <input type="radio" id="faculty" name="role" value="faculty">
        <label for="faculty">Faculty</label>
    </div>

    <input type="submit" value="Signup" name="submit_student">
    
    <div class="login-message">
        Already have an account? <a href="index.php">Login here</a>.
    </div>
</form>

</body>
</html>
