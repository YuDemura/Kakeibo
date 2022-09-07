<?php
session_start();
$_SESSION['error'] = '';

$user_id = $_SESSION['user_id'];

$name = filter_input(INPUT_POST, 'name');
$category_id = filter_input(INPUT_POST, 'category_id');
$amount = filter_input(INPUT_POST, 'amount');
$accrual_date = filter_input(INPUT_POST, 'accrual_date');

if (empty($_POST['name']) || empty($_POST['category_id']) || empty($_POST['accrual_date']) || empty($_POST['amount'])) {
    $_SESSION['error'] = '支出名、カテゴリー、金額または日付を入力してください';
    header('Location: ./create.php');
    exit();
}

require_once __DIR__ . '/../utils/pdo.php';
$sql = 'INSERT INTO spendings(name, user_id, category_id, amount, accrual_date) VALUES(:name, :user_id, :category_id, :amount, :accrual_date)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$statement->bindValue(':amount', $amount, PDO::PARAM_INT);
$statement->bindValue(':accrual_date', $accrual_date, PDO::PARAM_STR);
$statement->execute();
header('Location: ./index.php');
exit();
