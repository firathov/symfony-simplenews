<?php

require_once "../vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Dotenv\Dotenv;

$path = '..'.DIRECTORY_SEPARATOR.'.env';

$dotenv = new Dotenv();
$dotenv->load($path);

$connectionParams = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => 'pdo_mysql',
];

try {
    $conn = DriverManager::getConnection($connectionParams);
} catch (\Doctrine\DBAL\Exception $e) {
    echo $e->getMessage();
}
$queryBuilder = $conn->createQueryBuilder();
$queryBuilder ->update('post', 'p')
    ->set('p.amount_of_upvotes', 0);
try {
    $queryBuilder->executeQuery();
    echo "Amount of upvote reverted";
} catch (\Doctrine\DBAL\Exception $e) {
    echo $e->getMessage();
}
exit;