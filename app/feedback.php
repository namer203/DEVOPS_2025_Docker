<?php
require 'db.php';


$trenutniUporabnik = [
    'id' => $_SESSION['user_id'] ?? null,
    'vloga' => $_SESSION['vloga'] ?? null,
];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mnenje = trim($_POST['mnenje'] ?? '');

    if ($mnenje === '') {
        $error = "Mnenje ne sme biti prazno.";
    } elseif (!$trenutniUporabnik['id']) {
        $error = "Moraš biti prijavljen, da lahko dodaš mnenje.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO mnenja (user_id, mnenje) VALUES (?, ?)");
        $stmt->execute([$trenutniUporabnik['id'], $mnenje]);
        $success = "Hvala za tvoje mnenje!";
    }
}

// prikaze zadnjih 10 mnenj z user_id in username
$stmt = $pdo->query("
    SELECT m.id, m.user_id, m.mnenje, m.datum, u.username
    FROM mnenja m
    LEFT JOIN uporabniki u ON m.user_id = u.id
    ORDER BY m.datum DESC
    LIMIT 10
");
$mnenja = $stmt->fetchAll();
?>

<!-- ni ratalo preko style.css -->
<style>
ul.mnenja-list {
  list-style-type: none;
  padding-left: 0;
  margin-left: 0;
}

ul.mnenja-list li {
  margin-bottom: 1.5em;
  padding-left: 0.5em;
  border-left: 3px solid #007BFF;
}
</style>

<h2>Povratne informacije</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post">
    <textarea name="mnenje" rows="4" cols="50" required></textarea><br>
    <button type="submit">Pošlji mnenje</button>
</form>

<h3>Zadnja mnenja:</h3>
<ul class="mnenja-list">
<?php foreach ($mnenja as $m): ?>
    <li>
        <strong><?= htmlspecialchars($m['username'] ?? 'Anonimni') ?></strong>
        (<?= htmlspecialchars($m['datum']) ?>):<br>
        <?= nl2br(htmlspecialchars($m['mnenje'])) ?>
        <?php
            $canDelete = ($trenutniUporabnik['vloga'] === 'admin') || ($trenutniUporabnik['id'] == $m['user_id']);
            if ($canDelete):
        ?>
            <form method="post" action="delete_mnenje.php" style="display:inline" onsubmit="return confirm('Izbriši mnenje?');">
                <input type="hidden" name="mnenje_id" value="<?= (int)$m['id'] ?>">
                <button type="submit" style="font-size: 0.8em; padding: 2px 6px; margin-left: 8px;">Izbriši</button>
            </form>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
