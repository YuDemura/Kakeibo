<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$categories_name = filter_input(INPUT_POST, 'categories_name');

$sqlCategory = 'select * from categories where name=:name';
$statement = $pdo->prepare($sqlCategory);
$statement->bindValue(':name', $categories_name, PDO::PARAM_STR);
$statement->execute();
$category = $statement->fetch();

if ($category) {
    $_SESSION['errors'][] = 'すでに登録済みのカテゴリです';
    header('Location: ./create.php');
    exit();
}


$sql = "INSERT INTO categories(name, user_id) VALUES (:name, :user_id)";
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $categories_name, PDO::PARAM_STR);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
header('Location: ./index.php');
exit();
