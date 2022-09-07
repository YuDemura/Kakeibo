<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:/user/signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$income_sources_id = filter_input(INPUT_GET, 'income_sources_id');
$accrual_date_start = filter_input(INPUT_GET, 'accrual_date_start');
$accrual_date_end = filter_input(INPUT_GET, 'accrual_date_end');

require_once __DIR__ . '/../utils/pdo.php';

$pullDownIncome_sources = <<<EOF
    SELECT
        id
        , name
    FROM
        income_sources
    WHERE
        user_id=:user_id
    ;
EOF;
$statement = $pdo->prepare($pullDownIncome_sources);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$income_sources= $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT incomes.id, incomes.income_source_id, incomes.amount, incomes.accrual_date, income_sources.name FROM incomes INNER JOIN income_sources ON incomes.income_source_id = income_sources.id WHERE incomes.user_id=:user_id";
if (!empty($income_sources_id)) {
    $sql = $sql . " and income_sources.id=:income_sources_id";
}
if (!empty($accrual_date_start)) {
    $sql = $sql . " and incomes.accrual_date >= :incomes_accrual_date_start";
}
if (!empty($accrual_date_end)) {
    $sql = $sql . " and incomes.accrual_date <= :incomes_accrual_date_end";
}
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
if (!empty($income_sources_id)) {
    $statement->bindValue(':income_sources_id', $income_sources_id, PDO::PARAM_INT);
}
if (!empty($accrual_date_start)) {
    $statement->bindValue(':incomes_accrual_date_start', $accrual_date_start, PDO::PARAM_STR);
}
if (!empty($accrual_date_end)) {
    $statement->bindValue(':incomes_accrual_date_end', $accrual_date_end, PDO::PARAM_STR);
}
$statement->execute();
$incomes = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>収入</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<?php require_once __DIR__ . '/../utils/header.php'; ?>

<body>
    <div>
        <h1 style="text-align:center">収入</h1>
    </div>
    <div>
        <?php $incomeTotal = array_sum(array_column($incomes, 'amount')); ?>
            合計額：<?php echo $incomeTotal . "円"; ?>
    </div>
    <div>
        <a class="text-blue-600 hover:underline" href="./create.php">収入を登録する</a><br>
    </div>
    <div class="bg-white rounded-lg shadow-md">
            <p class="text-gray-900 font-medium title-font">絞り込み検索</p>
            <form action="" method="get">
                <div class="flex flex-wrap mb-2">
                        <div class="relative">
                            <label>
                                収入源：
                            <select name="income_sources_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">収入源を選んで下さい</option>
                                <?php foreach ($income_sources as $income_source): ?>
                                    <option value="<?php echo $income_source['id']; ?>"><?php echo $income_source['name']; ?></option>
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
                <th scope="col" class="px-6 py-3 border">収入名</th>
                <th scope="col" class="px-6 py-3 border">金額</th>
                <th scope="col" class="px-6 py-3 border">日付</th>
                <th scope="col" class="px-6 py-3 border">編集</th>
                <th scope="col" class="px-6 py-3 border">削除</th>
            </tr>
            </thead>
            <tbody class="bg-[#f3f4f6]">
            <?php foreach ($incomes as $income): ?>
                <tr>
                    <td class="px-6 py-4 border"><?php echo $income['name']; ?></td>
                    <td class="px-6 py-4 border"><?php echo $income['amount']; ?></td>
                    <td class="px-6 py-4 border"><?php echo $income['accrual_date']; ?></td>
                    <td class="px-6 py-4  text-right border"><a href="./edit.php?id=<?php echo $income['id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">編集</a></td>
                    <td class="px-6 py-4 text-right border"><a href="./delete.php?id=<?php echo $income[
                'id'
            ]; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline">削除</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
