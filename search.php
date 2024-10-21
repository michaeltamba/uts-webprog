<?php
include 'config.php';
session_start(); // Pastikan session dimulai

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Variabel untuk menyimpan hasil pencarian
$searchResults = '';
$searchQuery = '';
$user_id = $_SESSION['user_id'];

// Cek apakah request GET dan parameter 'search' tersedia
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = htmlspecialchars($search); // Sanitasi query pencarian

    // Persiapkan pernyataan SQL untuk mencari todo_list
    $query = "SELECT * FROM todo_lists WHERE title LIKE :search AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $searchParam = "%" . $search . "%";

    // Bind parameter ke pernyataan SQL
    $stmt->bindParam(':search', $searchParam);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Eksekusi pernyataan
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil hasil sebagai array asosiatif

    // Membungkus hasil pencarian dalam card
    if (count($result) > 0) {
        $searchResults .= "<h4 class='text-success mb-4'>Found " . count($result) . " to-do list(s)</h4>";
        $searchResults .= "<div class='list-group'>";
        foreach ($result as $todo_list) {
            $searchResults .= "<a href='#' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-dark text-white border-secondary'>";
            $searchResults .= "<span>" . htmlspecialchars($todo_list['title']) . "</span>"; // Tampilkan judul
            $searchResults .= "<span class='badge bg-info rounded-pill'>To-Do List</span>";
            $searchResults .= "</a>";
        }
        $searchResults .= "</div>";
    } else {
        $searchResults .= "<h4 class='text-danger mb-4'>No to-do lists found for your search.</h4>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search To-Do Lists</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card bg-dark text-white shadow-lg p-4 mb-5 rounded border-secondary">
            <div class="card-body">
              <h2 class="text-center text-info mb-4">Search Your To-Do Lists</h2>
              <form method="GET" class="input-group input-group-lg">
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary mr-1" placeholder="What to-do list are you looking for?" aria-label="Search To-Do Lists" required>
                <button type="submit" class="btn btn-info px-4">Search</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-5 text-white">
        <div class='card bg-dark border-secondary mb-4'> <!-- Card untuk hasil pencarian -->
            <div class='card-body'>
                <?php if (!empty($searchQuery)): ?>
                    <h4 class='text-info'>Search Query: <?php echo $searchQuery; ?></h4>
                <?php endif; ?>
                <?php echo $searchResults; ?>
            </div> <!-- Tutup card-body -->
        </div> <!-- Tutup card -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
