<?php
require_once "../connect.php";
session_start();

$title = isset($_POST["title"]) ? $_POST["title"] : false;
$descr = isset($_POST["descr"]) ? $_POST["descr"] : false;
$user_id = $_SESSION["id_user"];
$id_task = isset($_GET["id"]) ? ($_GET["id"]) : false;

$notedb = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tasks WHERE id = $id_task"));

var_dump($notedb);

if ($title && $descr && $id_task) {
    if ($title != $notedb['title'] or $descr != $notedb['description'] ) {

            $sql = "UPDATE tasks SET user_id='$user_id', title='$title', description='$descr' WHERE id='$id_task'";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $_SESSION["message"] = "Успех!";
            } else {
                $_SESSION["message"] = "Ошибка: " . mysqli_error($con);
            }
        } else {
            $_SESSION["message"] = "Отредактируйте данные!";

        }

    header("Location: /personal.php");
} else {
    $_SESSION["message"] = "Ошибка!";
}