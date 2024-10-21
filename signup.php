<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        // Insert user ke dalam database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            $success = "Registrasi berhasil! Anda bisa <a href='login.php'>login</a> sekarang.";
        } else {
            $error = "Terjadi kesalahan saat registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #181a1f;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signup-container {
            background-color: #24272e;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .signup-container h2 {
            color: #fff;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .signup-container p {
            color: #8b8b8b;
            margin-bottom: 30px;
        }

        .signup-container a {
            color: #00d2ff;
            text-decoration: none;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 80%;
            padding: 15px;
            background-color: #2f343e;
            border: none;
            border-radius: 30px;
            padding-left: 50px;
            color: #fff;
            outline: none;
        }

        .input-group input::placeholder {
            color: #b8b9bd;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #00a2ff;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(45deg, #007bff, #00d2ff);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(45deg, #005cbf, #00a2ff);
        }

        .alert {
            border-radius: 20px;
            padding: 15px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #ff4d4d;
        }

        .alert-success {
            background-color: #28a745;
        }

    </style>
</head>
<body>

<div class="signup-container">
    <h2>Create new account</h2>
    <p>Already A Member? <a href="login.php">Log In</a></p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Enter username" required>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn">Create account</button>
    </form>
</div>

</body>
</html>