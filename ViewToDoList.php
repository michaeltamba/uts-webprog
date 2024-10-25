<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ToDoList.php');
    exit;
}

$todo_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM todo_lists WHERE id = ? AND user_id = ?");
$stmt->execute([$todo_id, $_SESSION['user_id']]);
$todo_list = $stmt->fetch();

if (!$todo_list) {
    header('Location: ToDoList.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_details'])) {
    $description = $_POST['description'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $note = $_POST['note'] ?? '';

    $stmt = $pdo->prepare("UPDATE todo_lists SET description = ?, deadline = ?, note = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$description, $deadline, $note, $todo_id, $_SESSION['user_id']]);

    header("Location: ViewToDoList.php?id=$todo_id");
    exit;
}

if (isset($_POST['toggle_status'])) {
    $new_status = $todo_list['status'] == 'incomplete' ? 'complete' : 'incomplete';
    $stmt = $pdo->prepare("UPDATE todo_lists SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $todo_id]);
    header("Location: ViewToDoList.php?id=$todo_id");
    exit;
}

$description = $todo_list['description'] ?? 'No description available.';
$deadline = $todo_list['deadline'] ?? 'No deadline set.';
$note = $todo_list['note'] ?? 'No notes available.';
$status = $todo_list['status'] ?? 'incomplete';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
  body {
    background-color: #DBD3D3;
}

.header {
    background: linear-gradient(45deg, #091057, #024CAA);
    padding: 30px;
    color: white;
    text-align: center;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease-in-out;
}

.btn-primary {
    background-color: #091057;
    border: none;
}

.btn-primary:hover {
    background-color: #024CAA;
}

.btn-info {
    background-color: #EC8305;
    border: none;
}

.btn-info:hover {
    background-color: #024CAA;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 12px;
}

.badge-complete {
    background-color: #28a745;
    color: white;
}

.badge-incomplete {
    background-color: #dc3545;
    color: white;
}

    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="header">
            <h1><?php echo htmlspecialchars($todo_list['title']); ?></h1>
        </div>
        <div class="card p-4">
            <div class="card-body">
                <h5 class="card-title">Details</h5>

                <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
                <p class="card-text"><strong>Deadline:</strong> <?php echo htmlspecialchars($deadline); ?></p>
                <p class="card-text"><strong>Note:</strong> <?php echo htmlspecialchars($note); ?></p>
                
                <p class="card-text">
                    <strong>Status:</strong>
                    <span class="status-badge <?php echo $status == 'complete' ? 'badge-complete' : 'badge-incomplete'; ?>">
                        <?php echo $status == 'complete' ? 'Complete' : 'Incomplete'; ?>
                    </span>
                </p>
                <form method="POST">
                    <button type="submit" name="toggle_status" class="btn btn-info mt-2">
                        Mark as <?php echo $status == 'complete' ? 'Incomplete' : 'Complete'; ?>
                    </button>
                </form>

                <hr>
                <h5 class="mt-4">Update To-Do List Details</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="form-control" value="<?php echo htmlspecialchars($deadline); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="2"><?php echo htmlspecialchars($note); ?></textarea>
                    </div>
                    <button type="submit" name="update_details" class="btn btn-primary">Update Details</button>
                </form>
                <a href="ToDoList.php" class="btn btn-secondary mt-3">Back to To-Do Lists</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
