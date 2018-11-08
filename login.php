<?php
require_once 'functions.php';
$errors = [];
if (!empty($_POST['login']) && !empty($_POST['password'])) {
    if (authorization($_POST['login'], $_POST['password'])) {
        header('Location: todo.php');
        die;
    } else {
        $errors[] = 'Неверный логин или пароль';
    }
}
if (!empty($_POST['newlogin'])) {
    if (checkExistedLogin($_POST['newlogin'])){
        header('Location: todo.php');
        die;
    } else {
        $errorsLogin[] = 'Такой логин уже существует';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Select из нескольких таблиц</title>
</head>
<body>
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-wrap">
                    <h1>Авторизация</h1>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Логин</label>
                            <input type="text" placeholder="Логин" name="login" id="lg" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="key" class="sr-only">Пароль</label>
                            <input type="password" placeholder="Пароль" name="password" id="key" class="form-control">
                        </div>
                        <input type="submit" id="btn-login" class="btn btn-success btn-lg btn-block" value="Войти">
                    </form>
                    <h3>Регистрация</h3>
                    <ul>
                        <?php if (isset($errorsLogin)) { foreach ($errorsLogin as $errorer): ?>
                            <li><?= $errorer ?></li>
                        <?php endforeach; } ?>
                    </ul>
                    <form method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Логин</label>
                            <input type="text" placeholder="Логин" name="newlogin" id="lg" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="lg" class="sr-only">Пароль</label>
                            <input type="password" placeholder="Пароль" name="newpassword" id="lg" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" name id="btn-login" class="btn btn-success btn-lg btn-block" value="Зарегистрироваться">
                        </div>
                    </form>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>