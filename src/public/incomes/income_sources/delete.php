<?php
require_once __DIR__ . '/../../utils/pdo.php';

$income_sources_id = filter_input(INPUT_GET, 'id');

$sql = "DELETE FROM income_sources where id=:income_sources_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':income_sources_id', $income_sources_id, PDO::PARAM_INT);
$statement->execute();
header('Location: ./index.php');
exit();
