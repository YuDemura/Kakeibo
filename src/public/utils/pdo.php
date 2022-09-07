<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=kakeibo; charset=utf8',
    $dbUserName,
    $dbPassword
);
