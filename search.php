<?php
include 'config.php';
session_start(); // Pastikan session dimulai

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = $_GET['search'] ?? '';
    $user_id = $_SESSION['user_id'] ?? 0; // Default to 0 if user_id is not set

    // Persiapkan pernyataan SQL untuk mencegah SQL injection
    $query = "SELECT * FROM Tasks WHERE task LIKE ? AND list_id IN (SELECT id FROM ToDoLists WHERE user_id = ?)";
    $stmt = $conn->prepare($query);
    $searchParam = "%" . $search . "%";
    $stmt->bind_param('si', $searchParam, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='container mt-5 text-white'>";
    if ($result->num_rows > 0) {
        echo "<h4 class='text-success mb-4'>Found " . $result->num_rows . " task(s)</h4>";
        echo "<div class='list-group'>";
        while ($task = $result->fetch_assoc()) {
            echo "<a href='#' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-dark text-white border-light shadow-lg'>";
            echo "<span>" . htmlspecialchars($task['task']) . "</span>"; // Escape output for safety
            echo "<span class='badge bg-info rounded-pill'>Task</span>";
            echo "</a>";
        }
        echo "</div>";
    } else {
        echo "<h4 class='text-danger mb-4'>No tasks found for your search.</h4>";
    }
    echo "</div>";

    $stmt->close(); // Tutup pernyataan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg p-4 mb-5 rounded border-light" style="background: linear-gradient(to right, #1e3c72, #2a5298);">
                <div class="card-body">
                    <h2 class="text-center text-light mb-4">Search Your Tasks</h2>
                    <form method="GET" class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary mr-1" placeholder="What task are you looking for?" aria-label="Search Tasks" required>
                        <button type="submit" class="btn btn-info px-4" style="background: linear-gradient(to right, #00c6ff, #0072ff); transition: background 0.3s ease;">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Kustom untuk Sentuhan Tambahan -->
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #121212; /* Latar belakang gelap */
}

.list-group-item {
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Efek hover untuk item daftar */
    transform: translateY(-2px); /* Efek sedikit mengangkat saat hover */
}

h4 {
    font-weight: 500; /* Menambah ketebalan font untuk judul */
}

.card {
    border-radius: 15px; /* Rounded corners untuk kartu */
}

input::placeholder {
    color: rgba(255, 255, 255, 0.7); /* Placeholder lebih cerah */
}
</style>
  
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

