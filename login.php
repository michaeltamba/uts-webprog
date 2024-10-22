<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Ambil user dari database berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
            background-color: #24272e;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            color: #fff;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .login-container p {
            color: #8b8b8b;
            margin-bottom: 30px;
        }

        .login-container a {
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

    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <p>Belum punya akun? <a href="signup.php">Daftar di sini</a></p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="ToDoList.php">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>