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
    $authorization = 'SELECT * FROM user WHERE login = :login AND password = :password';
    $stmt = $pdo->prepare($authorization);
    $stmt->execute(["login" => "$login", "password" => "$password"]);
    $users = $stmt->fetchAll();
    foreach ($users as $user) {
        if (isset($user)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            return true;
        } else {
            return false;
        }

    }
}

function checkExistedLogin($login)
{
    global $pdo;
    $checkExistedLogin = 'SELECT id FROM user WHERE login= ?';
    $stmt = $pdo->prepare($checkExistedLogin);
    $stmt->execute(["$login"]);
    $logins = $stmt->fetchAll();
    foreach ($logins as $login) {
        if (isset($login)) {
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
    $_SESSION['user_login'] = $_POST['newlogin'];

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

function deleteTask($user_id, $id) {
    global $pdo;
    $deleteTask = 'DELETE FROM task WHERE user_id= :user_id AND id= :id LIMIT 1';
    $stmt = $pdo->prepare($deleteTask);
    $stmt->execute(["user_id" => $user_id, "id" => $id]);
}

function getTasks ($user_id) {
    global $pdo;
    $getTable = 'SELECT id, description, assigned_user_id, date_added, is_done FROM task WHERE user_id= ? ORDER BY date_added ASC';
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

function getUsers() {
    global $pdo;
    $getUsers = 'SELECT login,id FROM user';
    $stmt = $pdo->prepare($getUsers);
    $stmt->execute();
    $users = $stmt->fetchAll();
    return $users;

}

function updateAssignedUser($assigned_id, $task_id, $user_id) {
    global $pdo;
    $updateAssignedUser = 'UPDATE task SET assigned_user_id= :assigned_id WHERE id= :task_id AND  user_id= :user_id';
    $stmt = $pdo->prepare($updateAssignedUser);
    $stmt->execute(["assigned_id" => $assigned_id, "task_id" => $task_id, "user_id" => $user_id]);
}

function getDeligatedTasks ($user_id) {
    global $pdo;
    $getDeligatedTasks = 'SELECT user_id, description, assigned_user_id, login FROM task t INNER JOIN user u ON u.id=t.assigned_user_id WHERE t.assigned_user_id = :user_id AND :user_id not in (SELECT t.user_id FROM task)';
    $stmt = $pdo->prepare($getDeligatedTasks);
    $stmt->execute(["user_id" => $user_id]);
    $deligates = $stmt->fetchAll();
    return $deligates;
}

function countTask ($user_id) {
    global $pdo;
    $nRows = $pdo->query('SELECT count(*) FROM task WHERE user_id = "' . "$user_id" . '"OR assigned_user_id ="' . "$user_id" . '"')->fetchColumn();
    echo $nRows;
}