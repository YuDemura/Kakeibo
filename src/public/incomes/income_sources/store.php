<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$income_sources_name = filter_input(INPUT_POST, 'income_sources_name');

$sql = "INSERT INTO income_sources(name, user_id) VALUES (:name, :user_id)";
$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $income_sources_name, PDO::PARAM_STR);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
header('Location: ./index.php');
exit();
