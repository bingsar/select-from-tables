<?php
require_once 'functions.php';

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

if (isset($_POST['task_id']))  {
    updateAssignedUser($_POST['assigned_user_id'], $_POST['task_id'], $_SESSION['user_id']);
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
                    <h1>Удалить задание</h1>
                    <form action="todo.php" method="POST">
                        <div class="form-group">
                            <label for="lg" class="sr-only">Описание</label>
                            <input type="text" placeholder="id" name="id" id="lg" class="form-control">
                        </div>
                        <input type="submit" id="btn-login" class="btn btn-success btn-lg btn-block" value="Удалить дело">
                    </form>
                    <br>
                    <a class="btn btn-danger btn-lg" href="logout.php">Выход</a>
                </div>
            </div>
                <div class="col-md-8">
                <table class="table table-bordered table-inverse">
                    <h3>Список заданий</h3>
                    <thead>
                    <tr>

                        <th>id</th>
                        <th>Задание</th>
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
                        </td>
                        <td>
                            <form action="todo.php" method="POST">
                                <input name="task_id" type="hidden" value="<?php echo $table['id']?>">
                                <select name="assigned_user_id">
                                    <?php foreach (getUsers() as $user){ ?>
                                        <option <?php if ($table['assigned_user_id'] == $user['id']):?>
                                            selected <?php endif; ?> value="<?= $user['id'] ?>">
                                            <?= $user['login'] ?>
                                            <?php } ?>
                                        </option>
                                </select>
                                <button type="submit">Делегировать</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                    <h3>Делегированные дела для пользователя - <?php echo $_SESSION['user_login']; ?></h3>
                    <table class="table table-bordered table-inverse">
                        <thead>
                        <tr>

                            <th>id Создателя</th>
                            <th>Описание задания</th>
                            <th>id Исполнителя</th>
                            <th>Имя исполнителя</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach (getDeligatedTasks($_SESSION['user_id']) as $deligated) {
                            ?>
                            <tr>
                                <td><?php echo $deligated['user_id']?></td>
                                <td><?php echo $deligated['description']?></td>
                                <td><?php echo $deligated['assigned_user_id']?></td>
                                <td><?php echo $deligated['login']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <h3>Количество дел: <?php countTask($_SESSION['user_id']); ?></h3>
            </div>
        </div>
    </div>
</section>
</body>
</html>