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
$filterStatus = ''; // Variabel untuk filter status
$user_id = $_SESSION['user_id'];

// Cek apakah request GET dan parameter 'search' dan 'filter' tersedia
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Dapatkan query pencarian dan filter status
    $search = $_GET['search'] ?? '';
    $filterStatus = $_GET['filter_status'] ?? 'all'; // Nilai default 'all'
    $searchQuery = htmlspecialchars($search); // Sanitasi query pencarian

    // Mulai query SQL dasar
    $query = "SELECT * FROM todo_lists WHERE title LIKE :search AND user_id = :user_id";

    // Tambahkan filter status jika dipilih
    if ($filterStatus == 'complete') {
        $query .= " AND status = 'complete'";
    } elseif ($filterStatus == 'incomplete') {
        $query .= " AND status = 'incomplete'";
    }

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
            // Tautkan judul ke ViewToDoList.php
            $searchResults .= "<a href='ViewToDoList.php?id=" . $todo_list['id'] . "' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-dark text-white border-secondary'>";
            $searchResults .= "<span>" . htmlspecialchars($todo_list['title']) . "</span>"; // Tampilkan judul
            $searchResults .= "<span class='badge bg-info rounded-pill'>" . ucfirst($todo_list['status']) . "</span>"; // Tampilkan status
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #091057;
            color: #DBD3D3;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin-top: 2rem;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #EC8305;
            color: #091057;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-control, .form-select {
            background-color: #024CAA;
            color: #DBD3D3;
            border-color: #DBD3D3;
            height: 40px;
            padding: 0.375rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #EC8305;
            box-shadow: 0 0 5px rgba(236, 131, 5, 0.5);
        }
        
        .btn-info {
            background-color: #EC8305;
            border-color: #EC8305;
            height: 40px;
            padding: 0 20px;
            font-size: 0.875rem;
        }
        .text-info {
            color: #EC8305 !important;
        }
        .border-secondary {
            border-color: #DBD3D3 !important;
        }
        .list-group-item {
            background-color: #024CAA;
            border: none;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }
        .list-group-item:hover {
            background-color: #091057;
            color: #EC8305;
        }
        .badge {
            background-color: #EC8305;
        }
        .input-group {
            display: flex;
        }
        .input-group .form-control, .input-group .form-select, .input-group .btn {
            margin-right: 10px;
        }
        .input-group .btn {
            margin-right: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-white shadow-lg p-4 mb-5 rounded border-secondary">
                    <div class="card-header text-white">
                        Search Your To-Do Lists
                    </div>
                    <div class="card-body">
                        <form method="GET" class="input-group">
                            <input type="text" name="search" class="form-control bg-white text-black border-secondary" placeholder="Search?" aria-label="Search To-Do Lists" required>
                            <select name="filter_status" class="form-select bg-white text-black border-secondary">
                                <option value="all" <?php echo ($filterStatus == 'all') ? 'selected' : ''; ?>>All</option>
                                <option value="complete" <?php echo ($filterStatus == 'complete') ? 'selected' : ''; ?>>Completed</option>
                                <option value="incomplete" <?php echo ($filterStatus == 'incomplete') ? 'selected' : ''; ?>>Incomplete</option>
                            </select>
                            <button type="submit" class="btn btn-info">Search</button>
                        </form>
                    </div>
                    <a href="ToDoList.php" class="btn text-white">Back to To-Do List</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5 text-white">
        <div class="card bg-dark border-secondary mb-4">
            <div class="card-body">
                <?php if (!empty($searchQuery)): ?>
                    <h4 class="text-info">Search Query: <?php echo $searchQuery; ?></h4>
                <?php endif; ?>
                <?php echo $searchResults; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





