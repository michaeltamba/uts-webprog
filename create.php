<?php
include 'config.php';

// Handle form submission with prepared statement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    
    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO todo_lists (title) VALUES (?)");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $stmt->close();
        header('Location: ToDoList.php');
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
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Create New To-Do List</h1>

        <form action="create.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">List Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter list title" required>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); // Protect against XSS ?></div>
        <?php endif; ?>

        <a href="ToDoList.php" class="btn btn-link mt-3">Back to To-Do Lists</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>