<?php
include 'config.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle delete action with prepared statement
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM todo_lists WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $_SESSION['user_id']]);
    header('Location: ToDoList.php');
    exit;
}

// Fetch all to-do lists for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM todo_lists WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$todo_lists = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesToDo.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Your To-Do Lists</h1>

    
   
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="profile.php" class="btn btn-primary">Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div>
            <a href="search.php" class="btn create-btn me">Search</a>
            <a href="create.php" class="btn create-btn">Create New To-Do List</a>
        </div>
    </div>
 

    <!-- Bootstrap Grid Layout for ToDo Cards -->
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php if (count($todo_lists) > 0): ?>
            <?php foreach ($todo_lists as $row) : ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="ViewToDoList.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text">
                                Created on: <?php echo htmlspecialchars(date('F d, Y', strtotime($row['created_at']))); ?>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <a href="ViewToDoList.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
                                View List
                            </a>
                            <a href="?delete_id=<?php echo $row['id']; ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Are you sure you want to delete this list?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <strong>No to-do lists found.</strong> Create your first one!
            </div>
        <?php endif; ?>
    </div>
        

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
