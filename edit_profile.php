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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    // Update data user, password hanya diperbarui jika diisi
    if ($password) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $email, $password, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);
    }

    // Redirect kembali ke halaman profil setelah update
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .profile-form h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #0284a2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #026b84;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }

            .btn {
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<div class="form-container">
    <form method="POST" action="" class="profile-form">
        <h2>Edit Profil</h2>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password Baru (Kosongkan jika tidak ingin mengubah):</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit" class="btn">Simpan Perubahan</button>
        <a href="profile.php" class="btn btn-back">Kembali ke Profil</a>
    </form>
</div>
</body>
</html>
