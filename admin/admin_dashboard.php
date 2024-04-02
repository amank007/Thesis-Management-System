<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add your additional styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #4CAF50; /* Pastel green */
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            z-index: 1;
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

        .container {
            text-align: center;
            margin-top: 80px; /* Adjust the margin based on your navbar height */
            flex: 1;
        }

        h2 {
            color: #333;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            text-decoration: none;
            color: #333;
            display: inline-block;
            width: 300px; /* Adjust as needed */
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .students-card {
            background-color: #4CAF50; /* Pastel green */
            color: white;
        }

        .faculty-card {
            background-color: #3498db; /* Pastel blue */
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- View Students Card -->
        <a href="view_student.php" class="card students-card">
            <i class="fas fa-users icon"></i>
            View Students
        </a>

        <!-- View Faculty Card -->
        <a href="view_faculty.php" class="card faculty-card">
            <i class="fas fa-chalkboard-teacher icon"></i>
            View Faculty
        </a>
    </div>
</body>
</html>
