<?php
include '../connection.php'; // Include the connection file

// Get the category ID from the URL
$id_kategori = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the category name
$query_category_name = "SELECT nama_kategori FROM kategori WHERE id_kategori = $id_kategori";
$result_category_name = $conn->query($query_category_name);
$category_name = $result_category_name->fetch_assoc()['nama_kategori'];

// Query to get articles by category
$query = "SELECT * FROM artikel WHERE id_kategori = $id_kategori ORDER BY tanggal DESC";
$result = $conn->query($query);

// Query to get categories
$query_categories = "SELECT * FROM kategori";
$result_categories = $conn->query($query_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= htmlspecialchars($category_name) ?> - Blog</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3399FF;">
        <div class="container">
        <a class="navbar-brand" href="bloghome.php" style="color: white;">Article Collection</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="bloghome.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <!-- <li class="nav-item"><a class="nav-link active" aria-current="page" href="bloghome.php">Blog</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="../AdminController/login.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page header with logo and tagline-->
    <header class="py-1 bg-light border-bottom mb-1">
        <div class="container">
            <div class="text-center my-5">
                <h1 class="fw-bolder"><?= htmlspecialchars($category_name) ?></h1>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card mb-4">
                        <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top"
                                src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="850"
                                height="350" /></a>
                        <div class="card-body">
                            <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                            <h2 class="card-title"><?= $row['judul'] ?></h2>
                            <p class="card-text"><?= substr($row['isi'], 0, 200) ?>...</p>
                            <a class="btn btn-info" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Read more →</a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"
                                aria-disabled="true">Newer</a></li>
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
                        <li class="page-item"><a class="page-link" href="#!">2</a></li>
                        <li class="page-item"><a class="page-link" href="#!">3</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                        <li class="page-item"><a class="page-link" href="#!">15</a></li>
                        <li class="page-item"><a class="page-link" href="#!">Older</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..."
                                aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-info" id="button-search" type="button">Go!</button>
                        </div>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($categories as $category) : ?>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0">
                                        <li><a href="category.php?id=<?= $category['id_kategori'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></a></li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer-->
    <footer class="py-5" style="background-color: #3399FF; mt-auto">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Article Collection 2024 by Nashifaanabila</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>
