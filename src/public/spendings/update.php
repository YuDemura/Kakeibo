<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$spending_id = filter_input(INPUT_POST, 'id');
$name = filter_input(INPUT_POST, 'name');
$category_id = filter_input(INPUT_POST, 'category_id');
$amount = filter_input(INPUT_POST, 'amount');
$accrual_date = filter_input(INPUT_POST, 'accrual_date');

$sql = 'UPDATE spendings SET name=:name, category_id=:category_id, amount=:amount, accrual_date=:accrual_date WHERE id=:spending_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':amount', $amount, PDO::PARAM_STR);
$statement->bindValue(':accrual_date', $accrual_date, PDO::PARAM_STR);
$statement->bindValue(':spending_id', $spending_id, PDO::PARAM_INT);
$statement->execute();
header("Location: ./index.php");
exit();
?>
