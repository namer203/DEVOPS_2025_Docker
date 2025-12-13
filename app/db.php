<?php
$host = 'db';
$db   = 'igra_app';
$user = 'igra_user'; //privzeto v xampp, brez gesla
$pass = 'geslo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Povezava z bazo ni uspela.');
}
?>
