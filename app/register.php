<?php
require_once("init.php");
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $geslo = password_hash($_POST['geslo'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO uporabniki (username, geslo) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $geslo]);
        header("Location: login.php");
    } catch (PDOException $e){
        echo "Uporabniško ime je že zasedeno.";
    }
}
?>
<form method="post">
    <h2>Registracija</h2>
    Uporabniško ime: <input name="username" required><br>
    Geslo: <input type="password" name="geslo" required><br>
    <button type="submit">Registriraj</button>
</form>