<?php
include '../config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Function to delete a user
function deleteUser($conn, $userId)
{
    $userId = mysqli_real_escape_string($conn, $userId);

    // Delete from students table
    $deleteStudentQuery = "DELETE FROM students WHERE email IN (SELECT email FROM users WHERE id = '$userId')";
    $conn->query($deleteStudentQuery);

    // Delete from users table
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    return $conn->query($deleteUserQuery);
}

// Function to fetch and display student details
function viewStudents($conn, $sortBy, $sortOrder)
{
    // Define the default sorting options
    $validColumns = ['id', 'email', 'pid', 'first_name', 'last_name', 'department_name', 'signup_timestamp'];
    $validOrders = ['ASC', 'DESC'];

    // Validate the sort column and order
    $sortBy = in_array($sortBy, $validColumns) ? $sortBy : 'signup_timestamp';
    $sortOrder = in_array(strtoupper($sortOrder), $validOrders) ? strtoupper($sortOrder) : 'ASC';

    $query = "SELECT users.id, users.email, students.pid, students.first_name, students.last_name, departments.department_name, users.signup_timestamp
              FROM users
              LEFT JOIN students ON users.email = students.email
              LEFT JOIN departments ON students.dept_id = departments.department_id
              WHERE users.role = 'student'
              ORDER BY $sortBy $sortOrder";

    $result = $conn->query($query);

    if ($result === false) {
        // Add error handling here
        die("Error executing the query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        echo '<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>View Students</title>
                  <!-- Add your additional styles here -->
                  <style>
                      body {
                          font-family: "Arial", "sans-serif";
                          background-color: #f4f4f4;
                          margin: 0;
                          padding: 0;
                          display: flex;
                          flex-wrap: wrap;
                          justify-content: center;
                      }

                      .card {
                          background-color: #fff;
                          border-radius: 8px;
                          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                          margin: 20px;
                          overflow: hidden;
                          width: 300px;
                          max-width: 100%;
                          transition: transform 0.3s;
                      }

                      .card:hover {
                          transform: scale(1.05);
                      }

                      .card-content {
                          padding: 16px;
                      }

                      .card-content h3 {
                          color: #333;
                          margin-bottom: 10px;
                      }

                      .card-content p {
                          color: #666;
                          margin: 0;
                      }

                      table {
                          width: 100%;
                      }

                      th, td {
                          padding: 10px;
                          text-align: left;
                      }

                      th {
                          background-color: #333;
                          color: white;
                      }

                      tr:hover {
                          background-color: #f5f5f5;
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
              <body>';

        include 'admin_navbar.php';

        echo '<h2 style="text-align: center;width:100%;">View Students</h2>';

        // Sorting options
        echo '<div class="sorting-options">
                <p>Sort by:
                    <a href="?sort=id&order=' . ($sortBy == 'id' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">ID</a> |
                    <a href="?sort=email&order=' . ($sortBy == 'email' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">Email</a> |
                    <a href="?sort=pid&order=' . ($sortBy == 'pid' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">PID</a> |
                    <a href="?sort=first_name&order=' . ($sortBy == 'first_name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">First Name</a> |
                    <a href="?sort=last_name&order=' . ($sortBy == 'last_name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">Last Name</a> |
                    <a href="?sort=department_name&order=' . ($sortBy == 'department_name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">Department</a> |
                    <a href="?sort=signup_timestamp&order=' . ($sortBy == 'signup_timestamp' && $sortOrder == 'ASC' ? 'DESC' : 'ASC') . '">Signup Timestamp</a>
                </p>
            </div>';

        while ($row = $result->fetch_assoc()) {
            echo '<section >
                    <div class="card">
                        <div class="card-content">
                            <h3>' . $row['first_name'] . ' ' . $row['last_name'] . '</h3>
                            <p>Email: ' . $row['email'] . '</p>
                            <p>PID: ' . $row['pid'] . '</p>
                            <p>Department: ' . $row['department_name'] . '</p>
                            <p>Signup Timestamp: ' . $row['signup_timestamp'] . '</p>
                            <p><a href="delete_student.php?delete_id=' . $row['id'] . '">Delete</a></p>
                        </div>
                      </div>
                </section>  
            ';
        }

        echo '</body></html>';
    } else {
        echo '<p>No students found.</p>';
    }
}

// Get the sorting parameters from the query string
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'signup_timestamp';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Call the function to display students with sorting
viewStudents($conn, $sortBy, $sortOrder);

// Close the connection after using it
$conn->close();
?>
