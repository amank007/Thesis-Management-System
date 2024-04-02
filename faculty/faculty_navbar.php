<?php
// Assuming session_start() has been called in your login process
// and $_SESSION["email"] is set after successful login
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <!-- Add your CSS styles or include a stylesheet if needed -->
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Light Gray */
        }

        .navbar {
            background-color: #2e6da4; /* Dark Blue */
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
            background-color: #4a90e2; /* Light Blue */
            color: white;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .welcome-message {
            color: white;
            margin-right: 15px;
        }

        .logout-btn {
            background-color: #4caf50; /* Green */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #45a049; /* Darker Green */
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="/faculty/faculty_dashboard.php">Home</a>
    
    <div class="navbar-right">
        <span class="welcome-message">Welcome, <?php echo $_SESSION["email"]; ?>!</span>
        <form action="../logout.php" method="post">
            <input type="submit" class="logout-btn" value="Logout">
        </form>
    </div>
</div>

<!-- Add the rest of your faculty dashboard content below -->

</body>
</html>
