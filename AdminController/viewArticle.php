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
    <title>Artikel</title>
    <!-- Favicon (using Bootstrap Icons) -->
    <link rel="icon" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/icons/newspaper.svg" type="image/svg+xml">
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
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
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
                    <h1 class="mt-4">Article</h1>
                    <br>
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambah">Add Article</button>
                    <br>
                    <br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Article List:
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Id Artikel</th>
                                        <th>Tanggal</th>
                                        <th>Author</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Isi</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                include "../connection.php";
                                $query = mysqli_query($conn, "SELECT artikel.*, kategori.nama_kategori 
                                FROM artikel 
                                JOIN kategori ON artikel.id_kategori = kategori.id_kategori 
                                ORDER BY artikel.id_artikel ASC");
                                $no = 1; // Initialize row counter
                                while ($row = mysqli_fetch_array($query)) :
                                ?>
                                    <tr>
                                        <td><?= $row['id_artikel'] ?></td>
                                        <td><?= $row['tanggal'] ?></td>
                                        <td><?= $row['penulis'] ?></td>
                                        <td><?= $row['nama_kategori'] ?></td>
                                        <td><?= $row['judul'] ?></td>
                                        <td><?= $row['isi'] ?></td>
                                        <td><img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" width="100"></td>
                                        <td>
                                            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#articleedit<?= $no ?>">Edit</a>
                                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#articlehapus<?= $no ?>">Delete</a>
                                        </td>
                                    </tr>

                                    <!-- Edit Article Modal -->
                                    <div class="modal fade" id="articleedit<?= $no ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Article</h5>
                                                </div>
                                                <form action="act_article.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="tid" value="<?= $row['id_artikel'] ?>">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="control-label" for="penulis">Author</label>
                                                            <input type="text" class="form-control" name="penulis" value="<?= $row['penulis'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="kategori">Category</label>
                                                            <select class="form-control" name="kategori" required>
                                                                <?php
                                                                $catQuery = mysqli_query($conn, "SELECT * FROM kategori");
                                                                while ($catRow = mysqli_fetch_array($catQuery)) {
                                                                    $selected = ($catRow['id_kategori'] == $row['id_kategori']) ? 'selected' : '';
                                                                    echo "<option value='{$catRow['id_kategori']}' $selected>{$catRow['nama_kategori']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="judul">Title</label>
                                                            <input type="text" class="form-control" name="judul" value="<?= $row['judul'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="isi">Content</label>
                                                            <textarea class="form-control" name="isi" rows="5" required><?= $row['isi'] ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="gambar">Image</label>
                                                            <input type="file" class="form-control" name="gambar">
                                                            <img src="data:image/jpeg;base64,<?= base64_encode($row['gambar']) ?>" width="100" class="mt-2">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-primary" type="submit" name="articleedit">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Article Modal -->
                                    <div class="modal fade" id="articlehapus<?= $no ?>" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Article</h5>
                                                </div>
                                                <form action="act_article.php" method="POST">
                                                    <input type="hidden" name="id_artikel" value="<?= $row['id_artikel'] ?>">
                                                    <div class="modal-body">
                                                        <div class="modal-footer">
                                                            <h5 class="text-center">Are you sure you want to delete this
                                                                article? <br><span class="text-danger"><?= $row['judul'] ?></span></h5>
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-primary" type="submit" name="articledelete">Delete</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $no++; // Increment row counter
                                endwhile;
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Article Modal -->
                <div class="modal fade" id="tambah" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Article</h5>
                            </div>
                            <form action="act_article.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="control-label" for="penulis">Author</label>
                                        <input type="text" class="form-control" name="penulis" placeholder="Enter author's name" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="kategori">Category</label>
                                        <select class="form-control" name="kategori" required>
                                            <?php
                                            $catQuery = mysqli_query($conn, "SELECT * FROM kategori");
                                            while ($catRow = mysqli_fetch_array($catQuery)) {
                                                echo "<option value='{$catRow['id_kategori']}'>{$catRow['nama_kategori']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="judul">Title</label>
                                        <input type="text" class="form-control" name="judul" placeholder="Enter the title" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="isi">Content</label>
                                        <textarea class="form-control" name="isi" rows="5" placeholder="Enter the content" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="gambar">Image</label>
                                        <input type="file" class="form-control" name="gambar" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit" name="articlesave">Add
                                            Article</button>
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