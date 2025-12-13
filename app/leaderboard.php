<?php
require 'db.php';

$trenutniUporabnik = [
    'id' => $_SESSION['user_id'] ?? null,
    'username' => $_SESSION['username'] ?? null,
    'vloga' => $_SESSION['vloga'] ?? null,
];

$igre = [
    'skupno'       => 'Skupna lestvica',
    'reakcijaIgra' => 'Reakcija',
    'timergame'    => 'Čas',
    'ujemi'        => 'Ujemi Klik',
    // ni krizcev krozcev saj ni neke konkurence da bi potreboval rezultate
];

$izbranaIgra = $_GET['game'] ?? 'skupno';

if ($izbranaIgra === 'skupno') {
    $stmt = $pdo->query("
        SELECT r.id, r.rezultat, r.datum, u.username, r.igra
        FROM rezultati r
        LEFT JOIN uporabniki u ON r.user_id = u.id
        ORDER BY r.rezultat ASC
        LIMIT 10
    ");
    $rezultati = $stmt->fetchAll();
} elseif (isset($igre[$izbranaIgra])) {
    $stmt = $pdo->prepare("
        SELECT r.id, r.rezultat, r.datum, u.username
        FROM rezultati r
        LEFT JOIN uporabniki u ON r.user_id = u.id
        WHERE r.igra = ?
        ORDER BY r.rezultat ASC
        LIMIT 10
    ");
    $stmt->execute([$izbranaIgra]);
    $rezultati = $stmt->fetchAll();
} else {
    header('Location: ?page=leaderboard&game=skupno');
    exit;
}
?>

<h1><?= htmlspecialchars($igre[$izbranaIgra]) ?></h1>

<p>
    Izberi igro:
    <?php foreach ($igre as $key => $ime): ?>
        <?php if ($key === $izbranaIgra): ?>
            <strong><?= htmlspecialchars($ime) ?></strong>
        <?php else: ?>
            <a href="?page=leaderboard&game=<?= urlencode($key) ?>"><?= htmlspecialchars($ime) ?></a>
        <?php endif; ?>
        &nbsp;|&nbsp;
    <?php endforeach; ?>
</p>

<table border="1" cellpadding="5" cellspacing="0" style="margin:auto; text-align:center;">
    <thead>
        <tr>
            <th>#</th>
            <th>Uporabnik</th>
            <th>Rezultat (točke/ms)</th>
            <th>Datum</th>
            <?php if ($izbranaIgra === 'skupno'): ?>
                <th>Igra</th>
            <?php endif; ?>
            <?php if ($trenutniUporabnik && $trenutniUporabnik['vloga'] === 'admin'): ?>
                <th>Dejanje</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (count($rezultati) === 0): ?>
            <tr>
                <td colspan="<?= ($izbranaIgra === 'skupno') ? 5 : 4 ?>" style="text-align:center; color:#666;">
                    Ni še rezultatov za to igro.
                </td>
            </tr>
        <?php else: ?>
            <?php $st = 1; foreach ($rezultati as $v): ?>
                <tr>
                    <td><?= $st ?></td>
                    <td><?= htmlspecialchars($v['username']) ?></td>
                    <td><?= (int)$v['rezultat'] ?></td>
                    <td><?= htmlspecialchars($v['datum']) ?></td>
                    <?php if ($izbranaIgra === 'skupno'): ?>
                        <td><?= htmlspecialchars($igre[$v['igra']] ?? $v['igra']) ?></td>
                    <?php endif; ?>
                    <?php if ($trenutniUporabnik && $trenutniUporabnik['vloga'] === 'admin'): ?>
                        <td>
                            <form method="post" action="delete_result.php" onsubmit="return confirm('Si prepričan, da želiš izbrisati ta rezultat?');">
                                <input type="hidden" name="rezultat_id" value="<?= (int)($v['id'] ?? 0) ?>">
                                <button type="submit">Izbriši</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php $st++; endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<p style="text-align:center;">
    <a href="?page=home"><- Nazaj na Home</a>
</p>
