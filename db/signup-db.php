<?php
require_once "../connect.php"; 
session_start(); 

$login = isset($_POST["login"]) ? $_POST["login"] : false; 
$pass = isset($_POST["pass"]) ? $_POST["pass"] : false; 

if ($login && $pass) { 
    $checkUser = mysqli_query($con, "SELECT * FROM users WHERE username='$login'");
    
    if (mysqli_num_rows($checkUser) > 0) {
        $_SESSION["message"] = "Пользователь уже существует!";
        header("Location: /");
    } else {
        $passHash = password_hash($pass, PASSWORD_DEFAULT); // Хешируем пароль.PASSWORD_DEFAULT — константа, используемая в функции password_hash() в PHP для хеширования паролей.
        $sql = mysqli_query($con, "INSERT INTO users (username, password_hash) VALUES ('$login', '$passHash')");
        
        if ($sql) {
            $_SESSION["message"] = "Успех!"; 
        } else {
            $_SESSION["message"] = "Ошибка при регистрации!";
        }
        header("Location: /");
    }
} else { 
    $_SESSION["message"] = "Заполните все поля!"; 
    header("Location: /"); 
}
