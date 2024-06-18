<?php
include '../connection.php';

if (isset($_POST['bedit'])) {
    $id = $_POST['tid'];
    $email = $_POST['temail'];
    $username = $_POST['tusername'];
    $password = $_POST['tpassword'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE user SET email='$email', username='$username', password='$password_hash' WHERE id_admin=$id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script> 
        alert('Data successfully updated');
        window.location.href = 'viewAdmin.php';
        </script>";
    } else {
        echo "<script> 
        alert('Data failed to update');
        window.location.href = 'viewAdmin.php';
        </script>";
    }
}

if (isset($_POST['bdelete'])) {
    $hapus = mysqli_query($conn, "DELETE FROM user WHERE id_admin = '$_POST[id_admin]'");

    if ($hapus) {
        echo "<script> 
        alert('Admin data deleted successfully');
        document.location='viewAdmin.php';
        </script>";
    } else {
        echo "<script> 
        alert('Admin data failed to delete');
        document.location='viewAdmin.php';
        </script>";
    }
}
?>