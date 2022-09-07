<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:/user/signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category_id = filter_input(INPUT_GET, 'category_id');
$accrual_date_start = filter_input(INPUT_GET, 'accrual_date_start');
$accrual_date_end = filter_input(INPUT_GET, 'accrual_date_end');

require_once __DIR__ . '/../utils/pdo.php';

$pullDownCategories = <<<EOF
    SELECT
        id
        , name
    FROM
        categories
    WHERE
        user_id=:user_id
    ;
EOF;
$statement = $pdo->prepare($pullDownCategories);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$categories= $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT spendings.id, spendings.name AS spendings_name, spendings.category_id, spendings.amount, spendings.accrual_date, categories.name AS categories_name FROM spendings INNER JOIN categories ON spendings.category_id = categories.id WHERE spendings.user_id=:user_id";
if (!empty($category_id)) {
    $sql = $sql . " and categories.id=:categories_id";
}
if (!empty($accrual_date_start)) {
    $sql = $sql . " and spendings.accrual_date >= :spendings_accrual_date_start";
}
if (!empty($accrual_date_end)) {
    $sql = $sql . " and spendings.accrual_date <= :spendings_accrual_date_end";
}
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
if (!empty($category_id)) {
    $statement->bindValue(':categories_id', $category_id, PDO::PARAM_INT);
}
if (!empty($accrual_date_start)) {
    $statement->bindValue(':spendings_accrual_date_start', $accrual_date_start, PDO::PARAM_STR);
}
if (!empty($accrual_date_end)) {
    $statement->bindValue(':spendings_accrual_date_end', $accrual_date_end, PDO::PARAM_STR);
}
$statement->execute();
$spendings = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>支出</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<?php require_once __DIR__ . '/../utils/header.php'; ?>

<body>
    <div>
        <h1 style="text-align:center">支出</h1>
    </div>
    <div>
        <?php $spendingsTotal = array_sum(array_column($spendings, 'amount')); ?>
            合計額：<?php echo $spendingsTotal . "円"; ?>
    </div>
    <div>
        <a class="text-blue-600 hover:underline" href="./create.php">支出を登録する</a><br>
    </div>
    <div class="bg-white rounded-lg shadow-md">
            <p class="text-gray-900 font-medium title-font">絞り込み検索</p>
            <form action="" method="get">
                <div class="flex flex-wrap mb-2">
                        <div class="relative">
                            <label>
                                カテゴリー：
                            <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">カテゴリーを選んで下さい</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </label>
                            <input class='border-2 border-gray-300' type="date" name="accrual_date_start">
                            <span>〜</span>
                            <input class='border-2 border-gray-300' type="date" name="accrual_date_end">

                            <button type="submit" class="text-white bg-indigo-500 border-0 py-1 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">検索</button>
                        </div>
                </div>
            </form>
    </div>
    <div>
        <table class="border text-gray-500 dark:text-gray-400">
            <thead class="text-lg text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr class="border">
            <th scope="col" class="px-6 py-3 border">支出名</th>
            <th scope="col" class="px-6 py-3 border">カテゴリー</th>
            <th scope="col" class="px-6 py-3 border">金額</th>
            <th scope="col" class="px-6 py-3 border">日付</th>
            <th scope="col" class="px-6 py-3 border">編集</th>
            <th scope="col" class="px-6 py-3 border">削除</th>
            </tr>
            </thead>
            <tbody class="bg-[#f3f4f6]">
            <?php foreach ($spendings as $spending): ?>
                <tr>
                    <td class="px-6 py-4 border"><?php echo $spending['spendings_name']; ?></td>
                    <td class="px-6 py-4 border"><?php echo $spending['categories_name']; ?></td>
                    <td class="px-6 py-4 border"><?php echo $spending['amount']; ?></td>
                    <td class="px-6 py-4 border"><?php echo $spending['accrual_date']; ?></td>
                    <td class="px-6 py-4 text-right border"><a href="./edit.php?id=<?php echo $spending['id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">編集</a></td>
                    <td class="px-6 py-4 text-right border"><a href="./delete.php?id=<?php echo $spending[
                'id'
            ]; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">削除</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
