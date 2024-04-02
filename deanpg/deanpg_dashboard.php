<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeanPG Dashboard</title>
    <!-- Add your CSS styles or include a stylesheet if needed -->
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Light Gray */
            transition: background-color 0.3s ease-in-out;
        }

        .navbar {
            background-color: #343a40; /* Dark Gray */
            overflow: hidden;
            transition: background-color 0.3s ease-in-out;
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
            background-color: #6c757d; /* Gray */
            color: #ffffff; /* White */
        }

        .navbar-right {
            float: right;
        }

        .welcome-message {
            color: #ffffff; /* White */
            margin: 14px 16px;
            float: right;
            transition: color 0.3s ease-in-out;
        }

        .logout-btn {
            background-color: #dc3545; /* Red */
            color: #ffffff; /* White */
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .logout-btn:hover {
            background-color: #c82333; /* Darker Red */
        }

        .dashboard-buttons {
            margin: 20px;
            text-align: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .dashboard-button {
            background-color: #28a745; /* Green */
            color: #ffffff; /* White */
            padding: 15px 30px;
            font-size: 16px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-button:hover {
            background-color: #218838; /* Dark Green */
        }

        .icon {
            margin-right: 10px;
            width: 20px; /* Adjust the width as needed */
            height: 20px; /* Adjust the height as needed */
        }

        .dashboard-card {
            background-color: #ffffff; /* White */
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            max-width: 100%;
            animation: fadeIn 0.5s ease-in-out;
            transition: box-shadow 0.3s ease-in-out;
        }

        .dashboard-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <?php include 'deanpg_navbar.php'; ?>

    <div class="dashboard-buttons">
        <div class="dashboard-card">
            <button onclick="location.href='view_accepted_sac.php'" class="dashboard-button">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                View Accepted SACs
            </button>
        </div>
        <div class="dashboard-card">
            <button onclick="location.href='requested_file.php'" class="dashboard-button">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12h-6m-6 0H2"></path>
                </svg>
                Requested Files
            </button>
        </div>
    </div>
</body>
</html>
