<?php
session_start();

require_once __DIR__ . '/../utils/pdo.php';

$user_id = $_SESSION['user_id'];
$income_id = filter_input(INPUT_GET, 'id');

$sql = "SELECT incomes.id, incomes.income_source_id, incomes.amount, incomes.accrual_date, income_sources.name FROM incomes INNER JOIN income_sources ON incomes.income_source_id = income_sources.id WHERE incomes.user_id=:user_id and incomes.id=:income_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->bindParam(':income_id', $income_id, PDO::PARAM_INT);
$statement->execute();
$income = $statement->fetch();

$sql = 'SELECT * FROM income_sources WHERE user_id=:user_id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$income_sources = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>収入編集</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="w-full h-screen">
    <div>
        <h1 style="text-align:center">収入編集</h1>
    </div>
    <div class="pt-10 pb-10 px-10 rounded-xl">
        <form action="./update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $income['id']; ?>">
        <div>
            <label>
            収入源：
            <select name="income_sources_id">
                <option value="<?php echo $income['income_source_id']; ?>" selected><?php echo $income['name']; ?></option>
                <?php foreach ($income_sources as $income_source): ?>
                <option value="<?php echo $income_source['id']; ?>"><?php echo $income_source['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </label>
        </div>
        <div>
            <label>
                金額
                <input class='border-2 border-gray-300' type="text" name="amount" value="<?php echo $income['amount']; ?>">
            </label>
        </div>
        <div>
            <label>
                日付
                <input class='border-2 border-gray-300' type="date" name="accrual_date" value="<?php echo $income['accrual_date']; ?>">
            </label>
        </div>
        <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mb-5' type="submit" name="button">編集</button>
        </form>
    </div>
</body>
</html>
