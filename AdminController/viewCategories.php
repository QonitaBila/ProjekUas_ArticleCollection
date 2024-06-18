<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Pengguna belum login, lakukan tindakan yang sesuai, seperti redirect ke halaman login
    header('Location: login.php');
    exit();
}

// Jika pengguna sudah login, maka dapatkan dan tampilkan username
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Kategori</title>
    <!-- Favicon (using Bootstrap Icons) -->
    <link rel="icon" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/icons/tags-fill.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="index.php"><h3>Dashboard</h3></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group"></div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="login.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="viewAdmin.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                            Admin
                        </a>
                        <a class="nav-link" href="viewCategories.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-tags"></i></div>
                            Category
                        </a>
                        <a class="nav-link" href="viewArticle.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-file-earmark-text"></i></div>
                            Article
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= $_SESSION['email'] ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Category</h1>
                    <br>
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambah">Add Category</button>
                    <br><br>
                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-table me-1"></i> Category List:</div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Id Kategori</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php
                                include "../connection.php";
                                $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori ASC");
                                $no = 1;  // Initialize row counter
                                while ($row = mysqli_fetch_array($query)) :
                                ?>
                                    <tr>
                                        <td><?= $row['id_kategori'] ?></td>
                                        <td><?= $row['nama_kategori'] ?></td>
                                        <td><?= $row['keterangan'] ?></td>
                                        <td>
                                            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#catedit<?= $no ?>">Edit</a>
                                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cathapus<?= $no ?>">Delete</a>
                                        </td>
                                    </tr>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="catedit<?= $no ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                                </div>
                                                <form action="act_category.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="tid" value="<?= $row['id_kategori'] ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="control-label" for="nama_kategori">Category
                                                                Name</label>
                                                            <input type="text" class="form-control" name="tkategori" value="<?= $row['nama_kategori'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="ket">Category
                                                                Description</label>
                                                            <input type="text" class="form-control" name="keterangan" value="<?= $row['keterangan'] ?>" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-primary" type="submit" name="catedit">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Category Modal -->
                                    <div class="modal fade" id="cathapus<?= $no ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                                                </div>
                                                <form action="act_category.php" method="POST">
                                                    <input type="hidden" name="id_kategori" value="<?= $row['id_kategori'] ?>">
                                                    <div class="modal-body">
                                                        <div class="modal-footer">
                                                            <h5 class="text-center">Are you sure you want to delete this
                                                                category? <br><span class="text-danger"><?= $row['nama_kategori'] ?></span>
                                                            </h5>
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-primary" type="submit" name="catdelete">Delete Category</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $no++;  // Increment row counter
                                endwhile;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Category Modal -->
                <div class="modal fade" id="tambah" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                            </div>
                            <form action="act_category.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="control-label" for="nama_kategori">Category Name</label>
                                        <input type="text" class="form-control" placeholder="Enter your Category" name="tkategori" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="keterangan">Category Description</label>
                                        <input type="text" class="form-control" placeholder="Enter your Description" name="ket" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit" name="catsave">Add
                                            Category</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
            <script src="js/datatables-simple-demo.js"></script>
</body>

</html>