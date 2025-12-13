<?php
require_once("init.php");
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $geslo = $_POST['geslo'];

    $stmt = $pdo->prepare("SELECT * FROM uporabniki WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($geslo, $user['geslo'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['vloga'] = $user['vloga'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Napačno uporabniško ime ali geslo.";
    }
}
?>

<form method="post">
    <h2>Prijava</h2>
    Uporabniško ime: <input name="username" required><br>
    Geslo: <input type="password" name="geslo" required><br>
    <button type="submit">Prijavi se</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
