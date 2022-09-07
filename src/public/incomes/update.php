<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$income_id = filter_input(INPUT_POST, 'id');
$income_sources_id = filter_input(INPUT_POST, 'income_sources_id');
$amount = filter_input(INPUT_POST, 'amount');
$accrual_date = filter_input(INPUT_POST, 'accrual_date');

$sql = 'UPDATE incomes SET income_source_id=:income_source_id, amount=:amount, accrual_date=:accrual_date WHERE id=:income_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':income_source_id', $income_sources_id, PDO::PARAM_INT);
$statement->bindValue(':amount', $amount, PDO::PARAM_STR);
$statement->bindValue(':accrual_date', $accrual_date, PDO::PARAM_STR);
$statement->bindValue(':income_id', $income_id, PDO::PARAM_INT);
$statement->execute();
header("Location: ./index.php");
exit();
?>
