<?php
session_start();
$_SESSION['error'] = '';

$user_id = $_SESSION['user_id'];

$income_sources_id = filter_input(INPUT_POST, 'income_sources_id');
$amount = filter_input(INPUT_POST, 'amount');
$accrual_date = filter_input(INPUT_POST, 'accrual_date');

if (empty($_POST['income_sources_id']) || empty($_POST['amount']) || empty($_POST['accrual_date'])) {
    $_SESSION['error'] = '収入源または金額または日付を入力してください';
    header('Location: ./create.php');
    exit();
}

require_once __DIR__ . '/../utils/pdo.php';
$sql = 'INSERT INTO incomes(user_id, income_source_id, amount, accrual_date) VALUES(:user_id, :income_source_id, :amount, :accrual_date)';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':income_source_id', $income_sources_id, PDO::PARAM_INT);
$statement->bindValue(':amount', $amount, PDO::PARAM_INT);
$statement->bindValue(':accrual_date', $accrual_date, PDO::PARAM_STR);
$statement->execute();
header('Location: ./index.php');
exit();
