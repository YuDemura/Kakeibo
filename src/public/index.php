<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:/user/signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$year = filter_input(INPUT_GET, 'year');

require_once __DIR__ . '/utils/pdo.php';

$incomesYearSql = <<<EOF
    SELECT DISTINCT
        DATE_FORMAT(accrual_date, '%Y') AS incomesYear
    FROM
        incomes
    ;
EOF;
$statement = $pdo->prepare($incomesYearSql);
$statement->execute();
$incomesYear= $statement->fetchAll(PDO::FETCH_ASSOC);
$years = [];
foreach ($incomesYear as $incomeYear) {
    foreach ($incomeYear as $key => $value)
    $years[] = $value;
}

$spendingsYearSql = <<<EOF
    SELECT DISTINCT
        DATE_FORMAT(accrual_date, '%Y') AS spendingsYear
    FROM
        spendings
    ;
EOF;
$statement = $pdo->prepare($spendingsYearSql);
$statement->execute();
$spendingsYear= $statement->fetchAll(PDO::FETCH_ASSOC);
$otherYears = [];
foreach ($spendingsYear as $spendingYear) {
    foreach ($spendingYear as $key => $value)
    $otherYears[] = $value;
}

$yearsList = array_unique(array_merge($years, $otherYears));
sort($yearsList);
$count = count($yearsList);

$incomesSql = <<<EOF
    SELECT
        DATE_FORMAT(accrual_date, '%c') AS Month
        , SUM(amount)
    FROM
        incomes
    WHERE
        user_id=:user_id
        AND
        DATE_FORMAT(accrual_date, '%Y') =:year
    GROUP BY
        Month
    ORDER BY
        cast(Month as SIGNED)
    ;
EOF;
$statement = $pdo->prepare($incomesSql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':year', $year, PDO::PARAM_STR);
$statement->execute();
$incomes= $statement->fetchAll(PDO::FETCH_ASSOC);

$spendingsSql = <<<EOF
    SELECT
        DATE_FORMAT(accrual_date, '%c') AS `Month`
        , SUM(amount)
    FROM
        spendings
    WHERE
        user_id=:user_id
        AND
        DATE_FORMAT(accrual_date, '%Y') =:year
    GROUP BY
        Month
    ORDER BY
        cast(Month as SIGNED)
    ;
EOF;
$statement = $pdo->prepare($spendingsSql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindValue(':year', $year, PDO::PARAM_STR);
$statement->execute();
$spendings= $statement->fetchAll(PDO::FETCH_ASSOC);

$totalBalances = [];
for ($i = 0; $i < 12; $i++) {
    $totalBalances[$i]['month'] = $i + 1;
    $totalBalances[$i]['incomes'] = 0;
    $totalBalances[$i]['spendings'] = 0;
    $totalBalances[$i]['balances'] = 0;
}

foreach ($incomes as $income) {
    $totalBalances[$income['Month']- 1]['incomes'] = $income['SUM(amount)'];
}

foreach ($spendings as $spending) {
    $totalBalances[$spending['Month']- 1]['spendings'] = $spending['SUM(amount)'];
    $totalBalances[$spending['Month']- 1]['balances'] = $totalBalances[$spending['Month']- 1]['incomes'] - $totalBalances[$spending['Month']- 1]['spendings'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>家計簿アプリ</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<?php require_once __DIR__ . '/utils/header.php'; ?>

<body>
    <div>
        <h1 style="text-align:center">家計簿アプリ</h1>
    </div>
    <div class="bg-white rounded-lg shadow-md">
        <form action="" method="get">
            <select name="year">
                <option value="">年を選んでください</option>
                <?php for ($i = 0; $i < $count; $i++) { ?>
                <option value="<?php echo $yearsList[$i]; ?>"><?php echo $yearsList[$i]; ?></option>
                <?php } ?>
            </select>
            <span>年の収支一覧</span>
            <button type="submit" class="text-white bg-indigo-500 border-0 py-1 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">検索</button>
        </form>
    </div>
    <div class="container px-5 mx-auto">
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2">月</th>
                <th class="px-4 py-2">収入</th>
                <th class="px-4 py-2">支出</th>
                <th class="px-4 py-2">収支</th>
            </tr>
            </thead>

            <?php foreach ($totalBalances as $totalBalance): ?>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $totalBalance['month']; ?></td>
                        <td class="border px-4 py-2"><?php echo $totalBalance['incomes']; ?></td>
                        <td class="border px-4 py-2"><?php echo $totalBalance['spendings']; ?></td>
                        <td class="border px-4 py-2"><?php echo $totalBalance['balances']; ?></td>
                    </tr>
                </tbody>
            <?php endforeach; ?>

        </table>
    </div>
</body>
</html>
