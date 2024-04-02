<?php

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "student") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .card-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .card {
            width: 200px;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s ease-in-out;
            text-align: center;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card button {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card i {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'student_navbar.php'; ?>

<div class="container">
    <h1>Welcome to the Student Dashboard, <?php echo $_SESSION["email"]; ?>!</h1>

    <div class="card-container">
        <div class="card">
            <i class="fas fa-user-plus"></i>
            <button onclick="location.href='student_register.php'">
                Register Yourself
            </button>
        </div>

        <div class="card">
            <i class="fas fa-briefcase"></i>
            <button onclick="location.href='sac_request.php'">
                Request SAC
            </button>
        </div>

        <div class="card">
            <i class="fas fa-eye"></i>
            <button onclick="location.href='my_sac.php'">
                View My SAC
            </button>
        </div>

        <div class="card">
            <i class="fas fa-cloud-upload-alt"></i>
            <button onclick="location.href='file_upload.php'">
                Upload Files
            </button>
        </div>

        <div class="card">
            <i class="fas fa-history"></i>
            <button onclick="location.href='sac_timeline.php'">
                My SAC Timeline
            </button>
        </div>
    </div>
</div>

</body>
</html>
