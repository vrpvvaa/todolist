<?php

require_once "../connect.php";  

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $taskId = $_POST['taskId']; 

    // Экранирование переменной для предотвращения SQL-инъекций
    $taskId = intval($taskId); // Приведение к целому числу

    // Формируем SQL-запрос
    $sql = "UPDATE tasks SET is_completed = 1 WHERE id = $taskId"; 

    // Выполняем запрос
    if ($con->query($sql) === TRUE) { 
        header("Location: /personal.php"); 
        exit(); 
    } else { 
        echo "Ошибка при обновлении статуса: " . $con->error; 
    } 
} 

