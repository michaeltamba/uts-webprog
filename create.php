<?php
include 'config.php';
session_start(); 

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session
    
    if (!empty($title)) {
        // Menggunakan PDO untuk menyiapkan query, sertakan user_id
        $stmt = $pdo->prepare("INSERT INTO todo_lists (title, user_id) VALUES (:title, :user_id)");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id ke query
        $stmt->execute(); // Eksekusi query
        header('Location: ToDoList.php');
        exit; // Pastikan untuk keluar setelah redirect
    } else {
        $error = "Title cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:#091057;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background-color: #DBD3D3;
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #ddd;
            border-radius: 0;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ff7eb3;
            background-color: #fff;
        }

        .form-label {
            color: #555;
            font-weight: bold;
        }

        .btn-custom {
            background-color: #EC8305;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #EC8305;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-back {
            color: #555;
            text-decoration: underline;
        }

        .alert {
            margin-top: 20px;
            border-radius: 8px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h1>Create New To-Do List</h1>

                    <form action="create.php" method="POST">
                        <div class="mb-4">
                            <label for="title" class="form-label">List Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter list title" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-custom">Create</button>
                        </div>
                    </form>

                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="ToDoList.php" class="btn-back">Back to To-Do Lists</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
