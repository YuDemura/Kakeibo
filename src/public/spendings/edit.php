<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$spendings_id = filter_input(INPUT_GET, 'id');

$sql = "SELECT spendings.id, spendings.name AS spendings_name, spendings.category_id, spendings.amount, spendings.accrual_date, categories.name AS categories_name  FROM spendings INNER JOIN categories ON spendings.category_id = categories.id WHERE spendings.user_id=:user_id and spendings.id=:spendings_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':spendings_id', $spendings_id, PDO::PARAM_INT);
$statement->execute();
$spending = $statement->fetch();

$sql = 'SELECT * FROM categories WHERE user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>支出編集</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="w-full h-screen">
    <div>
        <h1 style="text-align:center">支出編集</h1>
    </div>
    <div class="pt-10 pb-10 px-10 rounded-xl">
        <form action="./update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $spending['id']; ?>">
        <div>
            <label>
                支出名
                <input class='border-2 border-gray-300' type="text" name="name" value="<?php echo $spending['spendings_name']; ?>">
            </label>
        </div>
        <div>
            <label>
            カテゴリー：
            <select name="category_id">
                <option value="<?php echo $spending['category_id']; ?>" selected><?php echo $spending['categories_name']; ?></option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </label>
        </div>
        <div>
            <label>
                金額
                <input class='border-2 border-gray-300' type="text" name="amount" value="<?php echo $spending['amount']; ?>">
            </label>
        </div>
        <div>
            <label>
                日付
                <input class='border-2 border-gray-300' type="date" name="accrual_date" value="<?php echo $spending['accrual_date']; ?>">
            </label>
        </div>
        <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-5' type="submit" name="button">編集</button>
        </form>
    </div>
</body>
</html>
