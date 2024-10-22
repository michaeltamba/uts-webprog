

<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user dari database berdasarkan session user_id
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #091057;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .profile-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .profile-card h2 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #333;
        }

        .profile-card p {
            margin: 8px 0;
            font-size: 16px;
            color: #555;
        }

        .profile-actions {
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #0284a2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 0 10px;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #026b84;
        }

        .btn-logout {
            background-color: #d9534f;
        }

        .btn-logout:hover {
            background-color: #c9302c;
        }
        
        .profile-action{
            background-color
        }

        @media (max-width: 600px) {
            .profile-container {
                padding: 20px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div class="profile-card">
        <h2>Profil Saya</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        
        <div class="profile-actions">
            <a href="edit_profile.php" class="btn">Edit Profil</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
    <div class="profile-actions">

    <a href="ToDoList.php" class="btn">Back to To-Do List</a>

</div>

</div>

</body>
</html>
