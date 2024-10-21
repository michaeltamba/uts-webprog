<?php
include 'config.php';

// Handle delete action with prepared statement
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM todo_lists WHERE id = ?");
    $stmt->execute([$delete_id]); // Jalankan query dengan parameter
    header('Location: ToDoList.php');
    exit;
}

// Fetch all to-do lists using prepared statement
$stmt = $pdo->prepare("SELECT * FROM todo_lists");
$stmt->execute();
$todo_lists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do Lists</h1>

        <div class="d-flex justify-content-end mb-3">
            <a href="create.php" class="btn btn-primary">Create New To-Do List</a>
        </div>

        <ul class="list-group">
            <?php foreach ($todo_lists as $row) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($row['title']); // Protect against XSS ?>
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this list?');">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>