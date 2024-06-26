<?php
// Include your database connection code
include_once('../config.php');

// Start session (assuming you have user sessions)
session_start();

// Check if the user is logged in as a dean
if (isset($_SESSION['role']) && $_SESSION['role'] == 'deanpg') {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get file ID from the form submission
        $file_id = $_POST['file_id'];

        // Fetch SAC information for the selected file
        $sacQuery = "SELECT sac.*, uploads.student_email
                     FROM sac
                     INNER JOIN uploads ON sac.student_email = uploads.student_email
                     WHERE uploads.file_id = ?";
        
        // Use prepared statement to prevent SQL injection
        $stmtSAC = mysqli_prepare($conn, $sacQuery);
        mysqli_stmt_bind_param($stmtSAC, "i", $file_id);
        mysqli_stmt_execute($stmtSAC);
        $resultSAC = mysqli_stmt_get_result($stmtSAC);

        // Check if SAC information is retrieved
        if ($rowSAC = mysqli_fetch_assoc($resultSAC)) {
            // Get SAC members' emails
            $co_advisor_email = $rowSAC['co_advisor_email'];
            $chairperson_email = $rowSAC['chairperson_email'];
            $member1_email = $rowSAC['member1_email'];
            $member2_email = $rowSAC['member2_email'];

            // Update file status to 'forwardedtomembers' for each SAC member
            $updateStatusQuery = "INSERT INTO file_status (file_id, status, timestamp_status, by_email)
                                  VALUES (?, 'forwardedtomembers', NOW(), ?)";
            
            // Use prepared statement to prevent SQL injection
            $stmtUpdateStatus = mysqli_prepare($conn, $updateStatusQuery);

            // Forward to Co-Advisor
            mysqli_stmt_bind_param($stmtUpdateStatus, "is", $file_id, $co_advisor_email);
            mysqli_stmt_execute($stmtUpdateStatus);

            // Forward to Chairperson
            mysqli_stmt_bind_param($stmtUpdateStatus, "is", $file_id, $chairperson_email);
            mysqli_stmt_execute($stmtUpdateStatus);

            // Forward to Member 1
            mysqli_stmt_bind_param($stmtUpdateStatus, "is", $file_id, $member1_email);
            mysqli_stmt_execute($stmtUpdateStatus);

            // Forward to Member 2
            mysqli_stmt_bind_param($stmtUpdateStatus, "is", $file_id, $member2_email);
            mysqli_stmt_execute($stmtUpdateStatus);

            // Close the statements
            mysqli_stmt_close($stmtSAC);
            mysqli_stmt_close($stmtUpdateStatus);

            // Redirect back to file_requested.php with a success message
            header("Location: file_requested.php?success=1");
            exit();
        } else {
            // Redirect back to file_requested.php with an error message
            header("Location: file_requested.php?error=1");
            exit();
        }
    } else {
        // Redirect back to file_requested.php if the form is not submitted
        header("Location: file_requested.php");
        exit();
    }
} else {
    // Redirect if not logged in as a dean
    header("Location: login.php");
    exit();
}
?>
