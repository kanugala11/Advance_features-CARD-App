<?php
include 'config.php';

// Initialize search variable
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Pagination setup
$limit = 5; // number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query with search and pagination
$sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("ssii", $searchParam, $searchParam, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Count total posts for pagination
$countSql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE ? OR content LIKE ?";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("ss", $searchParam, $searchParam);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts - Search & Pagination</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="text-center mb-4">Blog Posts</h2>

    <!-- Search Form -->
    <form method="GET" class="d-flex mb-4">
        <input 
            type="text" 
            name="search" 
            class="form-control me-2" 
            placeholder="Search posts..." 
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Display Posts -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 150))) . '...'; ?>
                            </p>
                            <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No posts found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav>
        <ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

</body>
</html>
