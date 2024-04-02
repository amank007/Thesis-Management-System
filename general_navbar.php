
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <!-- Add your stylesheet link or styling here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #B0E0E6; /* Pastel blue */
            overflow: hidden;
            color: #6B8E23; /* Pastel olive green */
            padding: 10px; /* Balanced padding */
        }

        .navbar a {
            float: left;
            display: block;
            color: #6B8E23; /* Pastel olive green */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            margin: 0 5px; /* Balanced margin */
        }

        .navbar a:hover {
            background-color: #F0FFF0; /* Honeydew - Light pastel green */
            color: #2F4F4F; /* Dark slate gray */
        }

        .navbar-right {
            float: right;
        }

        .navbar button {
            background-color: #FFB6C1; /* Light pink */
            color: #4B0082; /* Indigo */
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin: 0 5px; /* Balanced margin */
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">Home</a>
    <div class="navbar-right">
        <button onclick="window.location.href='login.php'">Login</button>
        <button onclick="window.location.href='signup.php'">Signup</button>
    </div>
</div>

<!-- Your website content goes here -->

</body>
</html>
