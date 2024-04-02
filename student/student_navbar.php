<?php
// Assuming session_start() has been called in your login process
// and $_SESSION["role"] is set after successful login

// Check if the user is logged in
if (!isset($_SESSION["role"])) {
    header("Location: login.php");
    exit();
}

// Check if the user is a student
if ($_SESSION["role"] !== "student") {
    // Redirect to the appropriate page (e.g., faculty dashboard)
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Navbar</title>
    <!-- Add your CSS styles here -->
    <style>
        /* Add your styles for the navbar */
        /* Example styles */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #4CAF50; /* Pastel green */
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 16px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #45a049; /* Slightly darker shade on hover */
        }

        .navbar-right {
            float: right;
            margin-right: 20px;
        }

        .navbar-right span {
            color: white;
            margin-right: 16px;
        }

        .navbar a, .navbar-right a {
            display: inline-block;
            transition: color 0.3s;
        }

        .navbar a:hover, .navbar-right a:hover {
            color: #eee; /* Lighter shade on hover */
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="student_dashboard.php">Home</a>
   
    <div class="navbar-right">
        <span>Welcome, <?php echo $_SESSION["email"]; ?></span>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<!-- Add the rest of your page content below -->

</body>
</html>
