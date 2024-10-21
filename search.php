<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = $_GET['search'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM Tasks WHERE task LIKE '%$search%' AND list_id IN (SELECT id FROM ToDoLists WHERE user_id = $user_id)";
    $result = mysqli_query($conn, $query);
    while ($task = mysqli_fetch_assoc($result)) {
        echo $task['task'];
    }
}

?>
<form method="GET">
  Search Tasks: <input type="text" name="search"><br>
  <input type="submit" value="Search">
</form>