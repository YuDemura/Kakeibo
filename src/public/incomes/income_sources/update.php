<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$income_sources_id = filter_input(INPUT_POST, 'id');
$income_sources_name = filter_input(INPUT_POST, 'income_sources_name');
$user_id = $_SESSION['user_id'];

$sql = 'UPDATE income_sources SET name=:income_sources_name WHERE id =:income_sources_id and user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':income_sources_name', $income_sources_name, PDO::PARAM_STR);
$statement->bindValue(':income_sources_id', $income_sources_id, PDO::PARAM_INT);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
header("Location: ./index.php?id=$income_sources_id");
exit();
?>
