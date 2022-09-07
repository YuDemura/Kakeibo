<?php
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

session_start();
$_SESSION['email'] = $email;

if (empty($email) || empty($password)) {
    $_SESSION['errors'] = 'パスワードとメールアドレスを入力してください';
    header('Location: ./signin.php');
    exit();
}

require_once __DIR__ . '/../utils/pdo.php';

$sql = 'select * from users where email = :email';
$statement = $pdo->prepare($sql);
$statement->bindValue(':email', $email, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!password_verify($password, $user['password'])) {
    $_SESSION['errors'] = 'メールアドレスまたはパスワードが違います';
    header('Location: ./signin.php');
    exit();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
header('Location: ../index.php');
exit();
