<?php
session_start();
include '../connection.php';

if (isset($_POST['submit'])) {
    $email = $_POST['inputEmail'];
    $password = $_POST['inputPassword'];

    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['login'] = true;
                $_SESSION['email'] = $row['email'];
                header('Location: index.php');
                exit();
            } else {
                echo "Invalid email or password";
                echo "<script>document.location='login.php';</script>";
            }
        } else {
            echo "Invalid email or password";
            echo "<script>document.location='login.php';</script>";
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
}
?>