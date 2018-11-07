<?php
require_once 'functions.php';
var_dump($_POST);
if (!isAuthorized()) {
    header('Location: login.php');
    die;
}

if (isset($_GET)) {
    getUpdateTask ($_GET['changeDone'], $_SESSION['user_id'], $_GET['id']);
}

if (isset($_POST['newlogin']) && isset($_POST['newpassword'])) {
    checkExistedLogin($_POST['newlogin']);
}

if (isset($_POST['description'])){
    addTask($_POST['description']);
}

$time=time();
$thetime = date('d.m.Y', $time);

if (isset($_POST['id']))  {
    deleteTask($_SESSION['user_id'], $_POST['id']);
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Todo list</title>
</head>
<body>


<section id="todolist">
    <div align="center" class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="form-wrap">
                    <h1>Добавление задания</h1>
                    <form action="todo.php" method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Описание</label>
                            <input type="text" placeholder="Что нужно сделать?" name="description" id="lg" class="form-control">
                        </div>
                        <input type="submit" id="btn-login" class="btn btn-success btn-lg btn-block" value="Добавить">
                    </form>
                    <hr>
                    <br>
                    <h1>Удалить задание</h1>
                    <form action="todo.php" method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Описание</label>
                            <input type="text" placeholder="id" name="id" id="lg" class="form-control">
                        </div>
                        <input type="submit" id="btn-login" class="btn btn-success btn-lg btn-block" value="Удалить дело">
                    </form>
                    <br>
                    <a class="btn btn-success" href="logout.php">Выход</a>
                </div>
            </div>
                <div class="col-md-8">
                <table class="table table-bordered table-inverse">
                    <h1>Список дел</h1>
                    <thead>
                    <tr>

                        <th>id</th>
                        <th>Дело</th>
                        <th>Когда</th>
                        <th>Выполненные | Невыполненные</th>
                        <th>Исполнитель</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach (getTasks($_SESSION['user_id']) as $table) {
                    ?>
                    <tr>
                        <td><?php echo $table['id']?></td>
                        <td><?php echo $table['description']?></td>
                        <td><?php echo $table['date_added']?></td>
                        <td><?php if ($table['is_done'] == 0) {
                            echo 'Не выполнено | '; ?><a href="todo.php?changeDone=1&id=<?php echo $table['id'];?>">Выполнить</a>
                            <?php } else {
                            echo 'Выполнено | ';?><a href="todo.php?changeDone=0&id=<?php echo $table['id'];?>">Сбросить</a>
                            <?php } ?>

                        <td>
                            <form action="todo.php" method="POST">
                                <input name="task_id" type="hidden" value="<?php echo $table['id']?>">

                                <select name="assigned_user_id">
                                    <?php foreach (getUsers() as $user): ?>
                                        <option <?php if ($table['assigned_user_id'] == $table['user_id']):?>
                                            selected<?php endif; ?> value="<?= $user['id'] ?>">
                                            <?= $user['login'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Делегировать</button>
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
</body>
</html>