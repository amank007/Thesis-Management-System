<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dean Page</title>
    <!-- Add your CSS styles or include a stylesheet if needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f5e9; /* Light Green */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            background-color: #333;
            padding: 15px;
            color: white;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .card-container {
            width: 80%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .card {
            width: calc(30% - 20px);
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .card p {
            margin: 0;
            color: #666;
        }

        .card button {
            background-color: #4caf50; /* Green */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #45a049;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include 'deanpg_navbar.php'; ?>

    <?php
    // Include your database connection code
    include_once('../config.php');

    // Start session (assuming you have user sessions)
    session_start();

    // Check if the user is logged in as a dean
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'deanpg') {
        // Fetch files with the latest status 'forwardedtodean' along with SAC information
        $filesQuery = "SELECT uploads.file_id, uploads.file_path, uploads.student_email, 
                                sac.chairperson_email, sac.advisor_email, sac.co_advisor_email,
                                file_status.timestamp_status
                        FROM uploads
                        INNER JOIN (
                            SELECT file_id, MAX(timestamp_status) AS max_timestamp
                            FROM file_status
                            WHERE status = 'forwardedtodean'
                            GROUP BY file_id
                        ) latest_status ON uploads.file_id = latest_status.file_id
                        INNER JOIN file_status ON latest_status.file_id = file_status.file_id AND latest_status.max_timestamp = file_status.timestamp_status
                        INNER JOIN sac ON uploads.student_email = sac.student_email
                        ORDER BY file_status.timestamp_status DESC";

        // Use prepared statement to prevent SQL injection
        $stmtFiles = mysqli_prepare($conn, $filesQuery);
        mysqli_stmt_execute($stmtFiles);
        $resultFiles = mysqli_stmt_get_result($stmtFiles);
        ?>

        <h2>Files with Latest Status 'Forwarded to Dean'</h2>
        <div class="card-container">
            <?php
            while ($rowFiles = mysqli_fetch_assoc($resultFiles)) {
                ?>
                <div class="card">
                    <h3>File ID: <?php echo $rowFiles['file_id']; ?></h3>
                    <p>Student Email: <?php echo $rowFiles['student_email']; ?></p>
                    <p>Chairperson: <?php echo $rowFiles['chairperson_email']; ?></p>
                    <p>Advisor: <?php echo $rowFiles['advisor_email']; ?></p>
                    <p>Co-Advisor: <?php echo $rowFiles['co_advisor_email']; ?></p>
                    <p>Timestamp: <?php echo $rowFiles['timestamp_status']; ?></p>
                    <form action='forward_to_member.php' method='POST'>
                        <input type='hidden' name='file_id' value='<?php echo $rowFiles['file_id']; ?>'>
                        <button type='submit'>Forward to SAC Member</button>
                    </form>
                    <!-- Add "View File" button -->
                    <form action='view_file.php' method='POST'>
                        <input type='hidden' name='file_id' value='<?php echo $rowFiles['file_id']; ?>'>
                        <button type='submit'>View File</button>
                    </form>
                </div>
            <?php
            }
            ?>
        </div>

        <?php
        // Close the statement
        mysqli_stmt_close($stmtFiles);
    } else {
        // Redirect if not logged in as a dean
        header("Location: login.php");
        exit();
    }
    ?>
</body>
</html>
