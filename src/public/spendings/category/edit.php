<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$categories_id = filter_input(INPUT_GET, 'id');

$sql = "select id, name from categories where user_id=:user_id and id=:categories_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':categories_id', $categories_id, PDO::PARAM_INT);
$statement->execute();
$category = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>カテゴリ編集</title>
</head>
<body>
<form action="./update.php" method="post">
<input type="hidden" name="id" value="<?php echo $category['id']; ?>">
<label>
  カテゴリ名：
    <input type="text" name="categories_name" value="<?php echo $category['name']; ?>">
    <p><button type="submit" name="button">更新</button></p>
</label>
</form>
</body>
</html>
