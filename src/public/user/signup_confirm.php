<?php
if (empty($_POST)) {
    header('Location: ./signup.php');
    exit();
} else {
    $email = filter_input(INPUT_POST, 'email');
    $name = filter_input(INPUT_POST, 'name');
    $password = filter_input(INPUT_POST, 'password');
    $password_conf = filter_input(INPUT_POST, 'password_conf');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>会員登録確認画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録確認画面</h1>
<form action="signup_complete.php" method="post">
<p>ユーザー名：<input type="text" name="name" value="<?php echo $name ?>"></p>
<p>メールアドレス：<input type="email" name="email" value="<?php echo $email ?>"></p>
<p>パスワード：<input type="text" name="password" value="<?php echo $password ?>"></p>
<input type="hidden" name="password_conf" value="<?PHP echo $password_conf ?>">
<input type="submit" value="送信">
</form>
</body>
</html>
