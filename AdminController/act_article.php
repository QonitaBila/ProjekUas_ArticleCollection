<?php
include '../connection.php';

// Set the timezone to Jakarta
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk mengubah format tanggal
function formatTanggal($tanggal)
{
    $hari = array(
        1 => 'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
    );

    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $split = explode('-', date('Y-m-d', strtotime($tanggal)));
    $tgl = date('d', strtotime($tanggal));

    $num = date('N', strtotime($tanggal));
    $jam = date('H:i', strtotime($tanggal));

    return $hari[$num] . ', ' . $tgl . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0] . ' | ' . $jam;
}

// Add Article
if (isset($_POST['articlesave'])) {
    $penulis = $_POST['penulis'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    // Get the current date and time
    $tanggal = formatTanggal(date('Y-m-d H:i:s'));

    // Upload image
    $file = $_FILES['gambar']['tmp_name'];
    if ($file) {
        $image = addslashes(file_get_contents($file));
    } else {
        echo "<script>
        alert('Image upload failed');
        document.location='viewArticle.php';
        </script>";
        exit();
    }

    $query = "INSERT INTO artikel (penulis, id_kategori, judul, isi, gambar, tanggal) VALUES ('$penulis', '$kategori', '$judul', '$isi', '$image', '$tanggal')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
        alert('Article added successfully');
        document.location='viewArticle.php';
        </script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>
        alert('Article addition failed: $error_message');
        document.location='viewArticle.php';
        </script>";
    }
}

// Edit Article
if (isset($_POST['articleedit'])) {
    $id = $_POST['tid'];
    $penulis = $_POST['penulis'];
    $kategori = $_POST['kategori'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];

    if ($_FILES['gambar']['tmp_name']) {
        // If a new image is uploaded
        $file = $_FILES['gambar']['tmp_name'];
        if ($file) {
            $image = addslashes(file_get_contents($file));
        } else {
            echo "<script>
            alert('Image upload failed');
            document.location='viewArticle.php';
            </script>";
            exit();
        }

        $query = "UPDATE artikel SET penulis='$penulis', id_kategori='$kategori', judul='$judul', isi='$isi', gambar='$image' WHERE id_artikel='$id'";
    } else {
        // If no new image is uploaded
        $query = "UPDATE artikel SET penulis='$penulis', id_kategori='$kategori', judul='$judul', isi='$isi' WHERE id_artikel='$id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
        alert('Article updated successfully');
        document.location='viewArticle.php';
        </script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>
        alert('Article update failed: $error_message');
        document.location='viewArticle.php';
        </script>";
    }
}

// Delete Article
if (isset($_POST['articledelete'])) {
    $id = $_POST['id_artikel'];

    $query = "DELETE FROM artikel WHERE id_artikel='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
        alert('Article deleted successfully');
        document.location='viewArticle.php';
        </script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>
        alert('Article deletion failed: $error_message');
        document.location='viewArticle.php';
        </script>";
    }
}
?>
