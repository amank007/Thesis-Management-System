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

        // Get current user's email
        $current_user_email = $_SESSION['email'];

        // Get review from the form submission (replace 'review' with the actual form field name)
        $review = $_POST['review'];

        // Insert review into the reviews table
        $insertReviewQuery = "INSERT INTO reviews (file_id, reviewer_email, review, review_timestamp)
                              VALUES (?, ?, ?, NOW())";

        // Use prepared statement to prevent SQL injection
        $stmtInsertReview = mysqli_prepare($conn, $insertReviewQuery);
        mysqli_stmt_bind_param($stmtInsertReview, "iss", $file_id, $current_user_email, $review);
        mysqli_stmt_execute($stmtInsertReview);

        // Update file status to 'reviewedbymember' for the current user
        $updateStatusQuery = "INSERT INTO file_status (file_id, status, timestamp_status, by_email)
                              VALUES (?, 'reviewedbymember', NOW(), ?)";

        // Use prepared statement to prevent SQL injection
        $stmtUpdateStatus = mysqli_prepare($conn, $updateStatusQuery);
        mysqli_stmt_bind_param($stmtUpdateStatus, "is", $file_id, $current_user_email);
        mysqli_stmt_execute($stmtUpdateStatus);

        // Close the statements
        mysqli_stmt_close($stmtInsertReview);
        mysqli_stmt_close($stmtUpdateStatus);

        // Redirect back to member_file_view.php with a success message
        header("Location: member_file_view.php?success=1");
        exit();
    } else {
        // Redirect back to member_file_view.php if the form is not submitted
        header("Location: member_file_view.php");
        exit();
    }
} else {
    // Redirect if not logged in as a SAC member
    header("Location: login.php");
    exit();
}
?>
