<?php
include 'config.php';
session_start();
$loginError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $checkUserQuery = "SELECT id, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($checkUserQuery);
    if ($result === false) {
        $loginError = "Error checking user: " . $conn->error;
    } else {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            if (password_verify($password, $hashedPassword)) {
                $_SESSION["user_id"] = $row['id'];
                $_SESSION["role"] = $row['role'];
                $_SESSION["email"] = $email;
                if ($row['role'] == "student") {
                    header("Location: /student/student_dashboard.php"); exit();} 
                    elseif ($row['role'] == "faculty") {
                    header("Location: /faculty/faculty_dashboard.php"); exit();}
                     elseif ($row['role'] == "admin") {
                    header("Location: /admin/admin_dashboard.php"); exit(); }
                     elseif ($row['role'] == "deanpg") {
                    header("Location: /deanpg/deanpg_dashboard.php");
                    exit();} 
                    else { $loginError = "Invalid role for the user."; }
            } else { $loginError = "Wrong email or password."; }} 
            else { $loginError = "Wrong email or password.";}}}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        input[type="submit"] {
            background-color: #4CAF50; /* Pastel green */
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .signup-message {
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Login">

    <div class="signup-message">
        New user? <a href="signup.php">Sign up here</a>.
    </div>
</form>

</body>
</html>
