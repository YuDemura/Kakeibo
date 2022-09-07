<?php
require_once __DIR__ . '/../utils/pdo.php';

session_start();
$user_id = $_SESSION['user_id'];

$sql = 'SELECT * FROM categories WHERE user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$error[] = $_SESSION['error'] ?? '';
$_SESSION['error'] = '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>支出登録</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="w-full h-screen">
    <div>
        <h1 style="text-align:center">支出登録</h1>
    </div>
    <div class="pt-10 pb-10 px-10 rounded-xl">
        <div>
            <?php foreach ($error as $error): ?>
                <p class="text-red-600 mb-5 text-center"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
        <form action="./store.php" method="post">
            <div>
            <label>
                支出名
                <input class='border-2 border-gray-300' type="text" name="name">
            </label>
            </div>
            <div>
            <label>
            カテゴリー：
            <select name="category_id">
                <option value="">カテゴリーを選んでください</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </label>
            <a class="text-blue-600 mb-2" href="./category/index.php">カテゴリー一覧へ</a>
            </div>
            <div>
            <label>
                金額
                <input class='border-2 border-gray-300' type="text" name="amount">
            </label>
            </div>
            <div>
            <label>
                日付
                <input class='border-2 border-gray-300' type="date" name="accrual_date">
            </label>
            </div>
            <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-5' type="submit" name="button">登録</button>
        </form>
        <a class="text-blue-600" href="./index.php">戻る</a>
    </div>
</body>
</html>
