<?php
include '../config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Function to delete a faculty member
function deleteFaculty($conn, $userId)
{
    $userId = mysqli_real_escape_string($conn, $userId);

    // Delete from faculty table
    $deleteFacultyQuery = "DELETE FROM faculty WHERE email IN (SELECT email FROM users WHERE id = '$userId')";
    $conn->query($deleteFacultyQuery);

    // Delete from users table
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    return $conn->query($deleteUserQuery);
}

// Handle faculty deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_id"])) {
    $userId = $_GET["delete_id"];
    if (deleteFaculty($conn, $userId)) {
        header("Location: view_faculty.php"); // Redirect to the view_faculty.php page after deletion
        exit();
    } else {
        echo "Error deleting faculty member.";
    }
}
?>
