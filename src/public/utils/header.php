<?php
session_start();
$name =$_SESSION['name'];
?>
<header>
<div class="w-full">
	<nav class="bg-blue-500 shadow-lg">
		<div class="md:flex items-center justify-between py-2 px-8 md:px-12">
            <a href="/index.php" class="text-white rounded hover:bg-gray-900 hover:text-gray-100 hover:font-medium py-2 px-2 md:mx-2">HOME</a>
            <a href="/incomes/index.php" class="text-white rounded hover:bg-gray-900 hover:text-gray-100 hover:font-medium py-2 px-2 md:mx-2">収入TOP</a>
            <a href="/spendings/index.php" class="text-white rounded hover:bg-gray-900 hover:text-gray-100 hover:font-medium py-2 px-2 md:mx-2">支出TOP</a>
            <a href="/user/logout.php" class="text-white rounded hover:bg-gray-900 hover:text-gray-100 hover:font-medium py-2 px-2 md:mx-2">ログアウト</a>
		</div>
	</nav>
</div>
</header>
