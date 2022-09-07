<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$categories_id = filter_input(INPUT_POST, 'id');
$categories_name = filter_input(INPUT_POST, 'categories_name');
$user_id = $_SESSION['user_id'];

$sql = 'UPDATE categories SET name=:categories_name WHERE id =:categories_id and user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':categories_name', $categories_name, PDO::PARAM_STR);
$statement->bindValue(':categories_id', $categories_id, PDO::PARAM_INT);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
header("Location: ./index.php?id=$categories_id");
exit();
?>
