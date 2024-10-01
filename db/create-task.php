<?php

require_once "../connect.php";
session_start();

$title = isset($_POST["title"]) ? $_POST["title"] : false;
$descr = isset($_POST["descr"]) ? $_POST["descr"] : false;
$user_id = $_SESSION["id_user"];


if ($title and $descr) {
    $sql = "INSERT INTO `tasks`( `user_id`, `title`, `description`)
     VALUES ('$user_id','$title','$descr')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $_SESSION["message"] = "Успех!";
        header("Location: /personal.php");
    } else {
        $_SESSION["message"] = "Ошибка создания заметки!";
        header("Location: /personal.php");
    }

} else {
    $_SESSION["message"] = "Заполните все поля!";
    header("Location: /personal.php");

}