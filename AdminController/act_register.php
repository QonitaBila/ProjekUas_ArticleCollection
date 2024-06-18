<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi password sama
    if ($password !== $confirm_password) {
        echo "<script>alert('Password and confirm password do not match'); window.location.href = 'register.php';</script>";
        exit();
    }

    // Hash password menggunakan password_hash()
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menyimpan data baru ke tabel user
    $query = "INSERT INTO user (email, username, password) VALUES ('$email', '$username', '$password_hash')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Registration successful'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Registration failed'); window.location.href = 'register.php';</script>";
    }
}
?>