<?php

include "header.php";

?>

<div class="container">
    <h1>Зарегистрироваться</h1>
    <form method="post" action="db\signup-db.php">
        <div class="mb-3">
            <label for="email" class="form-label">Эл. почта</label>
            <input name="login" type="email" class="form-control" id="email" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Пароль</label>
            <input name="pass" type="password" class="form-control" id="pass">
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>

    <p class="sign-link"> 
        Уже зарегистрированы? <a href="sign-in.php">Войти</a>
    </p>
</div>

</body>

</html>
