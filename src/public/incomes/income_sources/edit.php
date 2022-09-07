<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$income_sources_id = filter_input(INPUT_GET, 'id');

$sql = "select id, name from income_sources where user_id=:user_id and id=:income_sources_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':income_sources_id', $income_sources_id, PDO::PARAM_INT);
$statement->execute();
$incomes = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>収入源編集</title>
</head>
<body>
<form action="./update.php" method="post">
<input type="hidden" name="id" value="<?php echo $incomes['id']; ?>">
<label>
    収入源：
    <input type="text" name="income_sources_name" value="<?php echo $incomes['name']; ?>">
    <p><button type="submit" name="button">更新</button></p>
</label>
</form>
</body>
</html>
