<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>カテゴリ追加</title>
</head>
<body>
    <h1>カテゴリ追加</h1>
    <form action="./store.php" method="post">
        <label>
            カテゴリ名：
            <input name="categories_name" type="text" placeholder="カテゴリ名">
        </label>
        <p><button type="submit" name="register">登録</button></p>
    </form>
        <a href="./index.php">戻る</a>
</body>
</html>
