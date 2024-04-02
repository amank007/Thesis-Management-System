<?php
// Include your database connection code
include_once('../config.php');

// Start session
session_start();

// Check if the user is logged in as a student
if (isset($_SESSION['role']) && $_SESSION['role'] == 'student') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process file upload
        $uploaded_file = $_FILES['file']['tmp_name'];

        // Check if file type is valid (you might want additional validation)
        // ...

        // Set the file path where the file will be stored
        $uploads_folder = '../uploads/';
        
        // Create a custom file name
        $custom_file_name = $_SESSION['email'] . '_' . $_POST['file_type'] . '_' . time() . '_' . uniqid() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        
        $file_path = $uploads_folder . $custom_file_name;

        // Move the uploaded file to the specified folder
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // Save file path to the database using prepared statements
            $stmt = mysqli_prepare($conn, "INSERT INTO uploads (student_email, file_path, file_type_id) VALUES (?, ?, ?)");

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "ssi", $student_email, $file_path, $file_type_id);

            // Set the parameter values
            $student_email = $_SESSION['email'];
            $file_type_id = $_POST['file_type']; // Assuming you have a form field for file type

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Get the last inserted file_id
                $last_inserted_file_id = mysqli_insert_id($conn);

                // Update file_status table with the same file_id
                $status = 'uploaded';
                $timestamp_status = date("Y-m-d H:i:s");
                $by_email = $_SESSION['email'];
                $updateFileStatusQuery = "INSERT INTO file_status (file_id, status, timestamp_status, by_email) VALUES ('$last_inserted_file_id', '$status', '$timestamp_status', '$by_email')";
                
                if (mysqli_query($conn, $updateFileStatusQuery)) {
                    echo "File uploaded successfully!";
                } else {
                    echo "Error updating file status.";
                }
            } else {
                echo "Error uploading file.";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error moving uploaded file.";
        }
    }
} else {
    // Redirect if not logged in as a student
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .navbar {
            background-color: #333;
            color: white;
            padding: 0;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            max-width: 100%;
            animation: fadeIn 0.5s ease-out;
            margin-top: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .card-content {
            padding: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease-out;
            border: none;
            padding: 10px;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php include 'student_navbar.php'; ?>
    </div>
    <div class="card">
        <div class="card-content">
            <h2 style="text-align: center; color: #333;">File Upload</h2>

            <form action="file_upload.php" method="post" enctype="multipart/form-data">
                <label for="file_type">Select File Type:</label>
                <select name="file_type" id="file_type">
                    <?php
                    // Fetch file types from the database
                    $fileTypeQuery = "SELECT type_id, type FROM file_type";
                    $fileTypeResult = mysqli_query($conn, $fileTypeQuery);

                    if ($fileTypeResult) {
                        while ($row = mysqli_fetch_assoc($fileTypeResult)) {
                            echo '<option value="' . $row['type_id'] . '">' . $row['type'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <label for="file">Choose File:</label>
                <input type="file" name="file" id="file" accept=".pdf">

                <input type="submit" value="Upload File">
            </form>
        </div>
    </div>
</body>
</html>
