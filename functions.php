<?php

session_start();

$db = 'todo';
$user = 'root';
$pass = '';
$host = 'localhost';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

$thetime = date('Y-m-d H:i:s' , time());

function authorization($login, $password)
{
   global $pdo;
    $authorization = 'SELECT * FROM user WHERE login = "' . $login . '"' . 'AND password = "' . $password . '"';
    foreach ($pdo->query($authorization) as $user) {
        if (isset($user)) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return false;
        }

    }
}

function checkExistedLogin($login)
{
    global $pdo;
    $checkExistedLogin = 'SELECT id FROM user WHERE login= "' . $login . '"';
    foreach ($pdo->query($checkExistedLogin) as $logins) {
        if (isset($logins)) {
            return false;
        }
    }
    if (empty($logins)) {
        addUser();
        return true;
    }
}

function addUser()
{
    global $pdo;
    $userLogin = $_POST['newlogin'];
    $userPass = $_POST['newpassword'];
    $addUser = 'INSERT INTO user(login, password) VALUES (:login, :password)';
    $stmt = $pdo->prepare($addUser);
    $stmt->execute(["login" => "$userLogin", "password" => "$userPass"]);
    $getSession = 'SELECT id FROM user WHERE login = ?';
    $stmt = $pdo->prepare($getSession);
    $stmt->execute(["$userLogin"]);
    $newUserSession = $stmt->fetch();
    $_SESSION['user_id'] = $newUserSession['id'];

}

function isAuthorized()
{
    if (!empty($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function addTask ($description) {
    global $pdo;
    global $thetime;
    $addTask = 'INSERT INTO task (user_id, assigned_user_id, description, date_added) VALUES (:user_id, :assigned_user_id, :description, :date_added)';
    $stmt = $pdo->prepare($addTask);
    $stmt->execute(["user_id" => $_SESSION['user_id'], "assigned_user_id" => $_SESSION['user_id'], "description" => $description, "date_added" => $thetime]);
}

function logout()
{
    session_destroy();
}

function deleteTask($userId, $id) {
    global $pdo;
    $deleteTask = 'DELETE FROM task WHERE user_id= :user_id AND id= :id LIMIT 1';
    $stmt = $pdo->prepare($deleteTask);
    $stmt->execute(["user_id" => $userId, "id" => $id]);
}

function getTasks ($user_id) {
    global $pdo;
    $getTable = 'SELECT id, description, date_added, is_done FROM task WHERE user_id= ? ORDER BY date_added DESC';
    $stmt = $pdo->prepare($getTable);
    $stmt->execute([$user_id]);
    $tables = $stmt->fetchAll();
    return $tables;

}


function getUpdateTask ($is_done, $user_id, $task_id) {
    global $pdo;
    $setTaskValue = 'UPDATE task SET is_done= :is_done WHERE user_id= :user_id AND id= :task_id LIMIT 1';
    $stmt = $pdo->prepare($setTaskValue);
    $stmt->execute(["is_done" => $is_done, "user_id" => $user_id, "task_id" => $task_id]);
}

function updateTask ($is_done, $user_id, $task_id) {

if ($is_done == 0) {
    echo 'Не выполнено';

    $z = 1;

    getUpdateTask($z, $user_id, $task_id);

} else {
    echo 'Выполнено';

    $z = 0;

    getUpdateTask($z, $user_id, $task_id);

}
}