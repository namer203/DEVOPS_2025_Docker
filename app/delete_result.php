<?php
if (session_status() === PHP_SESSION_NONE) {
    require_once("init.php");
}

require_once 'db.php';

if (!isset($_SESSION['vloga']) || $_SESSION['vloga'] !== 'admin') {
    http_response_code(403);
    echo "Dostop zavrnjen.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rezultat_id'])) {
    $id = (int)$_POST['rezultat_id'];

    $stmt = $pdo->prepare("DELETE FROM rezultati WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'leaderboard.php'));
    exit;
}