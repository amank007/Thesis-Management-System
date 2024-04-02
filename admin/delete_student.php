<?php
include '../config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Function to delete a student
function deleteStudent($conn, $userId)
{
    $userId = mysqli_real_escape_string($conn, $userId);

    // Delete from students table
    $deleteStudentQuery = "DELETE FROM students WHERE email IN (SELECT email FROM users WHERE id = '$userId')";
    $conn->query($deleteStudentQuery);

    // Delete from users table
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    return $conn->query($deleteUserQuery);
}

// Handle student deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_id"])) {
    $userId = $_GET["delete_id"];
    if (deleteStudent($conn, $userId)) {
        header("Location: view_student.php"); // Redirect to the view_students.php page after deletion
        exit();
    } else {
        echo "Error deleting student.";
    }
}

// Close the connection after using it
$conn->close();
?>
