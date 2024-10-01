<?php
require_once "../header.php";
require_once "../connect.php";
session_start();

$login = isset($_POST["login"]) ? $_POST["login"] : false;

$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;

if ($login and $pass) {
    $sql = "SELECT * FROM `users` WHERE `username` = '$login'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) != 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($pass, $user["password_hash"])) { //расхэширование пароля
            $_SESSION["id_user"] = $user["id"]; 
            $_SESSION["message"] = "Успех!";
            header("Location: /personal.php");
        } else {
            $_SESSION["message"] = "Неверный пароль.";
            header("Location: /");
        }
    } else {
        $_SESSION["message"] = "Неверный логин.";
        header("Location: /");
    }
} else {
    $_SESSION["message"] = "Заполните все поля!";
    header("Location: /");
}
