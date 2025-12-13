<?php
header('Content-Type: application/json');
require_once("init.php");
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Niste prijavljeni']);
    exit;
}


$input = json_decode(file_get_contents('php://input'), true);

$score = $input['rezultat'] ?? null;
$game = $input['igra'] ?? null;

if (!$score || !$game) {
    echo json_encode(['success' => false, 'error' => 'Manjkajoči podatki']);
    exit;
}

// vstavi v db
try {
    $stmt = $pdo->prepare("INSERT INTO rezultati (user_id, rezultat, igra, datum) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$_SESSION['user_id'], $score, $game]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>