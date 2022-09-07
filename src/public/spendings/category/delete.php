<?php
require_once __DIR__ . '/../../utils/pdo.php';

$categories_id = filter_input(INPUT_GET, 'id');

$sql = "DELETE FROM categories where id=:categories_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':categories_id', $categories_id, PDO::PARAM_INT);
$statement->execute();
header('Location: ./index.php');
exit();
