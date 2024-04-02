<?php
include '../config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Function to fetch and display faculty members
function displayFaculty($conn, $sortColumn, $sortOrder)
{
    // Define the default sorting options
    $validColumns = ['id', 'email', 'role', 'first_name', 'last_name', 'specialization', 'department_name', 'signup_timestamp'];
    $validOrders = ['ASC', 'DESC'];

    // Validate the sort column and order
    $sortColumn = in_array($sortColumn, $validColumns) ? $sortColumn : 'id';
    $sortOrder = in_array(strtoupper($sortOrder), $validOrders) ? strtoupper($sortOrder) : 'ASC';

    $query = "SELECT users.id, users.email, users.role, faculty.first_name, faculty.last_name, faculty.specialization, departments.department_name, users.signup_timestamp
              FROM users
              LEFT JOIN faculty ON users.email = faculty.email
              LEFT JOIN departments ON faculty.dept_id = departments.department_id
              WHERE users.role = 'faculty'
              ORDER BY $sortColumn $sortOrder";

    $result = $conn->query($query);

    if ($result === false) {
        // Add error handling here
        die("Error executing the query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="faculty-card">
                    <div class="delete-btn">
                        <a href="delete_faculty.php?delete_id=' . $row['id'] . '">Delete</a>
                    </div>
                    <p>ID: ' . $row['id'] . '</p>
                    <p>Email: ' . $row['email'] . '</p>
                    <p>Role: ' . $row['role'] . '</p>
                    <p>First Name: ' . $row['first_name'] . '</p>
                    <p>Last Name: ' . $row['last_name'] . '</p>
                    <p>Specialization: ' . $row['specialization'] . '</p>
                    <p>Department: ' . $row['department_name'] . '</p>
                    <p>Signup Timestamp: ' . $row['signup_timestamp'] . '</p>
                  </div>';
        }
    } else {
        echo '<p>No faculty members found.</p>';
    }
}

// Get the sorting parameters from the query string
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Faculty</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add your additional styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .container {
            text-align: center;
        }

        .faculty-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
            text-decoration: none;
            color: #333;
            display: inline-block;
            width: 40%; /* Adjust the width as needed */
            transition: transform 0.3s;
            position: relative;
        }

        .faculty-card:hover {
            transform: scale(1.05);
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c; /* Red color */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #c0392b; /* Darker red color on hover */
        }

        .sorting-options {
            margin-top: 20px;
        }

        .sorting-options a {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            transition: background-color 0.3s;
        }

        .sorting-options a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <h2>View Faculty</h2>

    <div class="sorting-options">
        <p>Sort by:
            <a href="?sort=id&order=<?php echo ($sortColumn == 'id' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">ID</a> |
            <a href="?sort=email&order=<?php echo ($sortColumn == 'email' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Email</a> |
            <a href="?sort=role&order=<?php echo ($sortColumn == 'role' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Role</a> |
            <a href="?sort=first_name&order=<?php echo ($sortColumn == 'first_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">First Name</a> |
            <a href="?sort=last_name&order=<?php echo ($sortColumn == 'last_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Last Name</a> |
            <a href="?sort=specialization&order=<?php echo ($sortColumn == 'specialization' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Specialization</a> |
            <a href="?sort=department_name&order=<?php echo ($sortColumn == 'department_name' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Department</a> |
            <a href="?sort=signup_timestamp&order=<?php echo ($sortColumn == 'signup_timestamp' && $sortOrder == 'ASC') ? 'DESC' : 'ASC'; ?>">Signup Timestamp</a>
        </p>
    </div>

    <div class="container">
        <?php
        // Call the function to display faculty with sorting
        displayFaculty($conn, $sortColumn, $sortOrder);
        ?>
    </div>

</body>
</html>
