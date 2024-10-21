<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = $_GET['search'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM Tasks WHERE task LIKE '%$search%' AND list_id IN (SELECT id FROM ToDoLists WHERE user_id = $user_id)";
    $result = mysqli_query($conn, $query);

    echo "<div class='container mt-4'>";
    if (mysqli_num_rows($result) > 0) {
        echo "<h4 class='text-success mb-3'>Found " . mysqli_num_rows($result) . " task(s)</h4>";
        echo "<ul class='list-group'>";
        while ($task = mysqli_fetch_assoc($result)) {
            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
            echo $task['task'];
            echo "<span class='badge bg-success rounded-pill'>Task</span>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<h4 class='text-danger mb-3'>No tasks found for your search.</h4>";
    }
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg p-3 mb-5 bg-white rounded">
          <div class="card-body">
            <h2 class="text-center text-primary mb-4">Search Your Tasks</h2>
            <form method="GET" class="d-flex">
              <input type="text" name="search" class="form-control form-control-lg me-2" placeholder="Enter task to search..." aria-label="Search Tasks" required>
              <button type="submit" class="btn btn-primary btn-lg">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</body>
</html>

