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
            background-color: #f0f5e9; /* Light Green */
        }

        .navbar {
            background-color: #468fa0; /* Teal */
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #72acb4; /* Light Teal */
            color: black;
        }

        .navbar-right {
            float: right;
        }

        .welcome-message {
            color: white;
            margin: 14px 16px;
            float: right;
        }

        .logout-btn {
            background-color: #d9534f; /* Red */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c9302c; /* Darker Red */
        }

        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
            text-align: center;
        }

        .dashboard-button {
            background-color: #77ab59; /* Olive Green */
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            margin: 10px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-button:hover {
            background-color: #6c9b4b; /* Darker Olive Green */
        }

        .icon {
            margin-right: 10px;
            width: 24px; /* Adjust icon size as needed */
            height: 24px;
        }

        .card {
            background-color: #ffffff; /* White */
            border: 1px solid #dee2e6; /* Light Gray */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-content {
            padding: 20px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <?php include 'faculty_navbar.php'; ?>

    <div class="dashboard-buttons">
    <div class="card" onclick="location.href='faculty_registration.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="8" y1="12" x2="16" y2="12"></line>
            <line x1="12" y1="8" x2="12" y2="16"></line>
        </svg>
        Register
    </div>
</div>
<div class="card" onclick="location.href='requested_sac.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12" y2="8"></line>
        </svg>
        Requested SAC
    </div>
</div>







<!-- Requested File Button -->
<div class="card" onclick="location.href='member_file_view.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
            <line x1="7" y1="2" x2="7" y2="22"></line>
            <line x1="17" y1="2" x2="17" y2="22"></line>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <line x1="2" y1="7" x2="7" y2="7"></line>
            <line x1="2" y1="17" x2="7" y2="17"></line>
            <line x1="17" y1="17" x2="22" y2="17"></line>
            <line x1="17" y1="7" x2="22" y2="7"></line>
        </svg>
        Requested File
    </div>
</div>

<!-- Advisor File Request Button -->
<div class="card" onclick="location.href='advisor_file_request.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
            <line x1="7" y1="2" x2="7" y2="22"></line>
            <line x1="17" y1="2" x2="17" y2="22"></line>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <line x1="2" y1="7" x2="7" y2="7"></line>
            <line x1="2" y1="17" x2="7" y2="17"></line>
            <line x1="17" y1="17" x2="22" y2="17"></line>
            <line x1="17" y1="7" x2="22" y2="7"></line>
        </svg>
        Advisor File Request
    </div>
</div>

<!-- Advisor SAC View Button -->
<div class="card" onclick="location.href='advisor_sacview.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="12" y1="12" x2="16" y2="12"></line>
        </svg>
        Advisor SAC View
    </div>
</div>

<!-- Co-Advisor SAC View Button -->
<div class="card" onclick="location.href='coadvisor_sacview.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="12" y1="12" x2="16" y2="12"></line>
        </svg>
        Co-Advisor SAC View
    </div>
</div>

<!-- Member SAC View Button -->
<div class="card" onclick="location.href='member_sacview.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="12" y1="12" x2="16" y2="12"></line>
        </svg>
        Member SAC View
    </div>
</div>

<!-- Chair Person SAC View Button -->
<div class="card" onclick="location.href='chairperson_sacview.php'" class="dashboard-button">
    <div class="card-content">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="12" y1="12" x2="16" y2="12"></line>
        </svg>
        Chair Person SAC View
    </div>
</div>



       

       
    </div>

    <!-- Add the rest of your faculty dashboard content below -->

</body>

</html>
