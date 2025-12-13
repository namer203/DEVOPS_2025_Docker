<?php
require_once("init.php");
require 'db.php';

$trenutniUporabnik = [
    'id' => $_SESSION['user_id'] ?? null,
    'vloga' => $_SESSION['vloga'] ?? null,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mnenje_id'])) {
    $mnenje_id = (int)$_POST['mnenje_id'];

    // ali mnenje obstaja in kdo ga je ustvaril
    $stmt = $pdo->prepare("SELECT user_id FROM mnenja WHERE id = ?");
    $stmt->execute([$mnenje_id]);
    $mnenje = $stmt->fetch();

    if (!$mnenje) {
        die('Mnenje ne obstaja.');
    }

    // admin ali avtor mnenja
    if ($trenutniUporabnik['vloga'] !== 'admin' && $trenutniUporabnik['id'] != $mnenje['user_id']) {
        die('Dostop zavrnjen.');
    }

    // brisi
    $stmt = $pdo->prepare("DELETE FROM mnenja WHERE id = ?");
    $stmt->execute([$mnenje_id]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
