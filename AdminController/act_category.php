<?php
include '../connection.php';

if (isset($_POST['catsave'])) {
    $tambah = mysqli_query($conn, "INSERT INTO kategori (nama_kategori, keterangan ) VALUES ('$_POST[tkategori]', '$_POST[ket]')");

    if ($tambah) {
        echo "<script> 
        alert('Added category was successful');
        document.location='viewCategories.php';
        </script>";
    } else {
        echo "<script> 
        alert('Add failed category');
        document.location='viewCategories.php';
        </script>";
    }
}

if (isset($_POST['catedit'])) {
    $id = $_POST['tid'];
    $namakat = $_POST['tkategori'];
    $ket = $_POST['keterangan'];

    $query = "UPDATE kategori SET nama_kategori='$namakat', keterangan='$ket' WHERE id_kategori=$id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script> 
        alert('Data successfully updated');
        window.location.href = 'viewCategories.php';
        </script>";
    } else {
        echo "<script> 
        alert('Data failed to update');
        window.location.href = 'viewCategories.php';
        </script>";
    }
}

if (isset($_POST['catdelete'])) {
    $hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = '$_POST[id_kategori]'");

    if ($hapus) {
        echo "<script> 
        alert('Delete category successful');
        document.location='viewCategories.php';
        </script>";
    } else {
        echo "<script> 
        alert('Delete category failed');
        document.location='viewCategories.php';
        </script>";
    }
}
?>