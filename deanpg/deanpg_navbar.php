<?php
// Start session (assuming you have user sessions)
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application Name</title>
    <!-- Add your CSS stylesheets or include Bootstrap if needed -->
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5; /* Light Gray */
            transition: background-color 0.3s ease-in-out;
        }

        .navbar {
            background-color: #4caf50; /* Green */
            overflow: hidden;
            transition: background-color 0.3s ease-in-out;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .navbar a {
            float: left;
            display: block;
            color: #ffffff; /* White */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .navbar a:hover {
            background-color: #45a049; /* Dark Green */
            color: #ffffff; /* White */
        }

        .navbar-right {
            float: right;
        }

        .navbar-right p {
            color: #ffffff; /* White */
            display: inline-block;
            margin: 0;
            padding: 14px 16px;
            transition: color 0.3s ease-in-out;
        }

        .navbar-right a {
            color: #ffffff; /* White */
            transition: color 0.3s ease-in-out;
        }

        .navbar-right a:hover {
            color: #d4d8db; /* Light Gray */
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="deanpg_dashboard.php">Home</a>
    <div class="navbar-right">
        <p>Welcome, Dean!</p>
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
