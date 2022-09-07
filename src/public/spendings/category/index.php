<?php
session_start();

require_once __DIR__ . '/../../utils/pdo.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, name FROM categories WHERE user_id=:user_id";
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
    <title>カテゴリ一覧</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="mb-3 block text-2xl font-medium text-[#07074D]">カテゴリ一覧</h1>
    <div>
        <a class="text-blue-600 hover:underline" href="./create.php">カテゴリを追加する</a>
    </div>
    <div>
        <table class="border text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr class="border">
                <th scope="col" class="px-6 py-3 border">カテゴリ</th>
                <th scope="col" class="px-6 py-3 border">編集</th>
                <th scope="col" class="px-6 py-3 border">削除</th>
            </tr>
            </thead>
            <tbody class="bg-[#f3f4f6]">
            <?php foreach ($categories as $category): ?>
            <tr>
                <td class="px-6 py-4 border"><?php echo $category['name']; ?></td>
                <td class="px-6 py-4 text-right border"><a href="./edit.php?id=<?php echo $category['id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">編集</a></td>
                <td class="px-6 py-4 text-right border"><a href="./delete.php?id=<?php echo $category[
                'id'
            ]; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">削除</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="../create.php">戻る</a><br>
</body>
</html>
