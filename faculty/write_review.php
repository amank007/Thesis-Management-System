<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a SAC member (replace 'sac_member' with your actual role name)
if (isset($_SESSION['role']) && $_SESSION['role'] == 'faculty') {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get file ID from the form submission
        $file_id = $_POST['file_id'];
        
        // Get review text from the form submission
        $review_text = $_POST['review_text'];

        // Insert review into the reviews table
        $insertReviewQuery = "INSERT INTO reviews (file_id, reviewer_email, review, review_timestamp)
                              VALUES (?, ?, ?, NOW())";

        // Use prepared statement to prevent SQL injection
        $stmtInsertReview = mysqli_prepare($conn, $insertReviewQuery);
        mysqli_stmt_bind_param($stmtInsertReview, "iss", $file_id, $_SESSION['email'], $review_text);
        mysqli_stmt_execute($stmtInsertReview);

        // Update file status to 'reviewedbymember'
        $updateStatusQuery = "UPDATE file_status
                              SET status = 'reviewedbymember', timestamp_status = NOW(), by_email = ?
                              WHERE file_id = ?";

        // Use prepared statement to prevent SQL injection
        $stmtUpdateStatus = mysqli_prepare($conn, $updateStatusQuery);
        mysqli_stmt_bind_param($stmtUpdateStatus, "si", $_SESSION['email'], $file_id);
        mysqli_stmt_execute($stmtUpdateStatus);

        // Close the statements
        mysqli_stmt_close($stmtInsertReview);
        mysqli_stmt_close($stmtUpdateStatus);

        // Redirect back to member_file_view.php with a success message
        header("Location: member_file_view.php?success=1");
        exit();
    }

    // Display the form to write a review
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Write Review</title>";
    echo "<style>";
    echo "body {";
    echo "    font-family: Arial, sans-serif;";
    echo "    margin: 0;";
    echo "    padding: 0;";
    echo "    background-color: #f8f9fa; /* Light Gray */";
    echo "    display: flex;";
    echo "    align-items: center;";
    echo "    justify-content: center;";
    echo "    height: 100vh;";
    echo "}";
    echo ".card {";
    echo "    max-width: 400px;";
    echo "    padding: 20px;";
    echo "    background-color: #ffffff; /* White */";
    echo "    border: 1px solid #dee2e6; /* Light Gray */";
    echo "    border-radius: 5px;";
    echo "    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo "form {";
    echo "    display: flex;";
    echo "    flex-direction: column;";
    echo "}";
    echo "label {";
    echo "    margin-bottom: 5px;";
    echo "}";
    echo "textarea, input {";
    echo "    padding: 8px;";
    echo "    margin-bottom: 10px;";
    echo "    border: 1px solid #dee2e6; /* Light Gray */";
    echo "    border-radius: 3px;";
    echo "    box-sizing: border-box;";
    echo "}";
    echo "input[type='submit'] {";
    echo "    background-color: #4caf50; /* Green */";
    echo "    color: white;";
    echo "    border: none;";
    echo "    border-radius: 5px;";
    echo "    padding: 10px;";
    echo "    cursor: pointer;";
    echo "    transition: background-color 0.3s;";
    echo "}";
    echo "input[type='submit']:hover {";
    echo "    background-color: #45a049; /* Darker Green */";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<div class='card'>";
    echo "<form method='POST' action='write_review.php'>";
    echo "<label for='review_text'>Write your review:</label><br>";
    echo "<textarea id='review_text' name='review_text' rows='4' cols='50' required></textarea><br>";
    echo "<input type='hidden' name='file_id' value='" . $_GET['file_id'] . "'>";
    echo "<input type='submit' value='Submit Review'>";
    echo "</form>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
} else {
    // Redirect if not logged in as a SAC member
    header("Location: login.php");
    exit();
}
?>
