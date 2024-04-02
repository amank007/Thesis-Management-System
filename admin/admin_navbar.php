<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
            display: flex;
            align-items: center;
        }

        .navbar-right p {
            color: white;
            margin: 0;
            padding: 14px 16px;
        }

        .navbar-right a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            transition: color 0.3s;
        }

        .navbar-right a:hover {
            color: #ddd; /* Slightly lighter shade on hover */
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="admin_dashboard.php">Home</a>
    <div class="navbar-right">
        <p>Welcome, <?php echo $_SESSION["email"]; ?> (Admin)</p>
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
