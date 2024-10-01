<?php

include "header.php";
require_once "connect.php";

$filterT = isset($_POST['taskFilter']) ? $_POST['taskFilter'] : '';
$filterD = isset($_POST['dateFilter']) ? $_POST['dateFilter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Начинаем формировать запрос
$query = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["id_user"];

if ($filterT === '1') {
  $query .= " AND is_completed = 1";
} elseif ($filterT === '0') {
  $query .= " AND is_completed = 0";
}

if (!empty($search)) {
  $query .= " AND (title LIKE '%$search%' OR description LIKE '%$search%')";
}

// Добавляем сортировку по дате
if ($filterD === '1') {
  $query .= " ORDER BY created_at DESC"; // Новые
} elseif ($filterD === '0') {
  $query .= " ORDER BY created_at ASC"; // Старые
}

$sql = mysqli_query($con, $query);

?>

<nav>

  <h1>TODO LIST</h1>
  <div class="menu-bar">

    <form action="" method="get">
      <div class="search-cont">
        <input name="search" class="search-line inter" placeholder="Поиск задачи..." name="search" type="text">
        <button class="b-zero search-b" type="submit"> <img src="images/icons/search.png" alt="Icon"> </button>
      </div>
    </form>
    
    <form action="" method="post">
      <select onchange="this.form.submit()" name="taskFilter" class="form-select">
        <button class="filter-btn" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
          aria-expanded="false">
          Все<span>&#x25BC;</span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
          <option value="" <?= $filterT === '' ? "selected" : '' ?>>Все</option>
          <option value="1" <?= $filterT === '1' ? "selected" : '' ?>>Выполненные</option>
          <option value="0" <?= $filterT === '0' ? "selected" : '' ?>>Не выполненные</option>
        </ul>
      </select>
    </form>

    <form action="" method="post">
        <select id="Filter" name="dateFilter" class="form-select" onchange="this.form.submit()">
          <option value="" <?= $filterD === '' ? "selected" : '' ?>>Все</option>
          <option value="1" <?= $filterD === '1' ? "selected" : '' ?>>Новые</option>
          <option value="0" <?= $filterD === '0' ? "selected" : '' ?>>Старые</option>
        </select>
    </form>

      <a href="sign-out.php" class="buttons b-zero change-theme"><img src="images\icons\exit.png" alt="Icon"></a>

  </div>

</nav>

<main>
  <section class="personal-section">
    <div class="content">
      <?php
      if (mysqli_num_rows($sql) != 0) {
        while ($app = mysqli_fetch_assoc($sql)) {
          ?>
          <div class="note">
            <div class="link">
                <input name="checkbox" value="<?= $app['id'] ?>" id="checkbox-<?= $app["id"] ?>" type="checkbox"
                  onChange="updateStatus(this)" <?= $app["is_completed"] == '1' ? "checked" : "" ?>>
              <div class="text">
                <form id="form" action="/db/edit.php?id=<?= $app["id"] ?>" method="post">
                  <input name="title" required type="text" value="<?= $app['title'] ?>">
                  <input name="descr" required type="text" value="<?= $app['description'] ?>">
                </form>
              </div>
            </div>
            <div class="button-group">
              <button id="submit-form" type="submit" form="form"><img src="images\icons\edit.png" alt="Icon"></button>
              <a href="#" class="delete" data-id="<?= $app["id"] ?>"><img src="images\icons\delete.png" alt="Icon"></a>
            </div>
          </div>
        <?php }
      } else { ?>
        <img src="images\icons\empty.png" alt="Zero">
        <p class="m-2">Кажется, Вам стоило бы создать пару задач..</p>
    <?php
      }
    ?>
    </div>
  </section>
</main>

<button class="buttons b-zero" id="create" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button"><img
    src="images/icons/plus.png" alt="Plus"></button>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="change-modal">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Новая задача</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="db/create-task.php">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Название задачи:</label>
            <input type="text" name="title" class="form-control" id="recipient-name" placeholder="Введите название...">
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Описание:</label>
            <input type="text" name="descr" class="form-control" id="recipient-name"
              placeholder="Введите вашу задачу...">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-primary" id="button">Создать</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
  function updateStatus(checkbox) {
    if (checkbox.checked) {
      var confirmation = confirm("Вы уверены, что хотите пометить эту заметку как завершенную?");
      if (confirmation) {
        $.ajax({
          url: '/db/update.php',
          method: 'POST',
          data: { taskId: checkbox.value },
          success: function (response) {
            console.log('Статус успешно обновлен');
          },
          error: function (xhr, status, error) {
            console.error('Ошибка при обновлении статуса:', error);
          }
        });
      } else {
        // Если пользователь отменил действие, оставляем галочку установленной
        checkbox.checked = true;
      }
    } else {
      // Если чекбокс не отмечен, предупреждаем пользователя
      alert("Вы не можете снять галочку с завершенной заметки.");
      checkbox.checked = true; // Снова устанавливаем галочку
    }
  }

  $(document).ready(function () {
    $('.delete').on('click', function (e) {
      e.preventDefault(); // Предотвращаем переход по ссылке 
      var taskId = $(this).data('id'); // Получаем ID задачи 

      $.ajax({
        url: '/db/delete.php',
        type: 'POST',
        data: { id: taskId },
        success: function (response) {
          console.log(response); // Для отладки
          if (response.trim() === 'success') {
            alert('Заметка успешно удалена');
            $('a.delete[data-id="' + taskId + '"]').closest('.note').fadeOut(300, function () {
              $(this).remove();
            });
          } else {
            alert('Ошибка при удалении заметки: ' + response);
          }
        },
        error: function () {
          alert('Ошибка при выполнении запроса');
        }
      });
    });
  });

  const currentTheme = localStorage.getItem('theme') || 'light';
  if
    (currentTheme === 'dark') {
    document.body.classList.add('dark-theme');
    document.getElementById('change-modal').classList.add('dark-theme');
  }

  document.getElementById('theme-toggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
    document.getElementById('change-modal').classList.toggle('dark-theme');
    const newTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
    localStorage.setItem('theme', newTheme);
  }); 
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>

</html>