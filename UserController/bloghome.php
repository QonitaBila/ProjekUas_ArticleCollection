<?php
include '../connection.php'; // Include the connection file

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Set up pagination variables
$non_featured_posts_per_page = 4;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $non_featured_posts_per_page;

// Adjust the query to include search functionality and pagination
if (!empty($search)) {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              WHERE artikel.judul LIKE '%$search%' 
              ORDER BY artikel.tanggal DESC 
              LIMIT $offset, $non_featured_posts_per_page";
} else {
    $query = "SELECT artikel.*, kategori.nama_kategori 
              FROM artikel 
              JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
              ORDER BY artikel.tanggal DESC 
              LIMIT $offset, $non_featured_posts_per_page";
}

$result = $conn->query($query);

// Query to get the total number of non-featured posts for pagination
$total_non_featured_query = "SELECT COUNT(*) as total 
                             FROM artikel 
                             WHERE judul LIKE '%$search%'";
$total_non_featured_result = $conn->query($total_non_featured_query);
$total_non_featured = $total_non_featured_result->fetch_assoc()['total'];
$total_pages = ceil($total_non_featured / $non_featured_posts_per_page);

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
    <title>Home</title>
    <!-- Favicon (using Bootstrap Icons) -->
    <link rel="icon" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/icons/house-fill.svg" type="image/svg+xml">
    <!-- tempat mengubah icon -->
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Responsive navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3399FF;">
        <!-- tempat mengubah warna navbar atas -->
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
                <h1 class="fw-bolder">Selamat Datang di Blog Memuat Artikel Tentang Kesenian, Adat, dan Wisata Kepulauan Madura</h1>
                <p class="lead mb-0">Kumpulan Artikel Tentang Kesenian, Adat, dan Wisata di Kepulauan Madura</p>
            </div>
        </div>
    </header>
    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">
                <?php
                $featured = true;
                $post_counter = 0;

                // Only show featured post on the first page
                if ($current_page == 1) {
                    $featured_query = "SELECT artikel.*, kategori.nama_kategori 
                                       FROM artikel 
                                       JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                                       ORDER BY artikel.tanggal DESC 
                                       LIMIT 1";
                    $featured_result = $conn->query($featured_query);
                    if ($featured_result->num_rows > 0) {
                        $featured_row = $featured_result->fetch_assoc();
                        ?>
                        <!-- Featured blog post-->
                        <div class="card mb-4">
                            <a href="blogpost.php?id=<?= $featured_row['id_artikel'] ?>"><img class="card-img-top" src="data:image/jpeg;base64,<?= base64_encode($featured_row['gambar']) ?>" alt="..." width="100%" height="350" /></a>
                            <div class="card-body">
                                <div class="small text-muted"><?= ($featured_row['tanggal']) ?></div>
                                <h2 class="card-title"><?= $featured_row['judul'] ?></h2>
                                <p class="card-text"><?= substr($featured_row['isi'], 0, 200) ?>...</p>
                                <a class="btn btn-info" href="blogpost.php?id=<?= $featured_row['id_artikel'] ?>">Read more →</a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <!-- Nested row for non-featured blog posts-->
                <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) : ?>
                        <div class="col-lg-6">
                            <!-- Blog post-->
                            <div class="card mb-4">
                                <a href="blogpost.php?id=<?= $row['id_artikel'] ?>"><img class="card-img-top" src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" alt="..." width="100%" height="350" /></a>
                                <div class="card-body">
                                    <div class="small text-muted"><?= ($row['tanggal']) ?></div>
                                    <h2 class="card-title h4"><?= $row['judul'] ?></h2>
                                    <p class="card-text"><?= substr($row['isi'], 0, 100) ?>...</p>
                                    <a class="btn btn-info" href="blogpost.php?id=<?= $row['id_artikel'] ?>">Read more →</a>
                                </div>
                            </div>
                        </div>
                    <?php
                        $post_counter++;
                        if ($post_counter % 2 == 0) :
                            echo '</div><div class="row">';
                        endif;
                    endwhile;
                    if ($post_counter % 2 != 0) :
                        echo '</div>'; // Close the last row if it's not closed
                    endif;
                } else {
                    echo "<p class='text-center'>No articles found matching your search criteria.</p>";
                }
                ?>
                </div>
                <!-- Pagination-->
                <nav aria-label="Pagination">
                    <hr class="my-0" />
                    <ul class="pagination justify-content-center my-4">
                        <?php if ($current_page > 1) : ?>
                            <li class="page-item"><a class="page-link" href="bloghome.php?page=<?= $current_page - 1 ?>&search=<?= $search ?>">&larr; Newer</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>"><a class="page-link" href="bloghome.php?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <?php if ($current_page < $total_pages) : ?>
                            <li class="page-item"><a class="page-link" href="bloghome.php?page=<?= $current_page + 1 ?>&search=<?= $search ?>">Older &rarr;</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
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
                                <button class="btn btn-light" id="button-search" type="submit" style="background-color: #0066FF; border-color: #0066FF; color: white;">Cari!</button>
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
                                <div class="col-sm-12">
                                    <ul class="list-unstyled mb-0">
                                        <li><a href="category.php?id=<?= $category['id_kategori'] ?>"><?= htmlspecialchars($category['nama_kategori']) ?></a></li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div> <!-- Close the side widgets column -->
        </div> <!-- Close the main row -->
    </div> <!-- Close the container -->
    <!-- Footer-->
    <footer class="py-5" style="background-color: #3399FF; mt-auto">
        <!-- mengubah warna footer -->
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
