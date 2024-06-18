<?php
include '../connection.php'; // Include the connection file

// Get the article ID from the URL
$id_artikel = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the article
$query = "SELECT artikel.*, kategori.nama_kategori 
          FROM artikel 
          JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
          WHERE artikel.id_artikel = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    die("Artikel tidak ditemukan.");
}

// Query to get the categories
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
    <title><?= htmlspecialchars($article['judul']) ?> - Blog Post</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .post-content p {
            text-align: justify;
        }
    </style>
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
    <!-- Page content-->
    <div class="container mt-5">
        <div class="row">
            <!-- Post content-->
            <div class="col-lg-8">
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1"><?= htmlspecialchars($article['judul']) ?></h1>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">Diposting pada
                            <?= htmlspecialchars($article['tanggal']) ?> oleh
                            <?= htmlspecialchars($article['penulis']) ?>
                        </div>
                        <!-- Post categories-->
                        <a class="badge bg-secondary text-decoration-none link-light" href="#!"><?= htmlspecialchars($article['nama_kategori']) ?></a>
                    </header>
                    <!-- Preview image figure-->
                    <figure class="mb-4"><img class="img-fluid rounded" src="data:image/jpeg;base64,<?= base64_encode($article['gambar']) ?>" alt="..." /></figure>
                    <!-- Post content-->
                    <section class="post-content mb-5">
                        <p class="fs-5 mb-4"><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
                    </section>
                </article>
            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Pencarian</div>
                    <div class="card-body">
                        <form method="GET" action="bloghome.php">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" placeholder="Cari Artikel..." aria-label="Cari Artikel..." aria-describedby="button-search" />
                                <button class="btn btn-info" id="button-search" type="submit">Cari!</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Kategori</div>
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