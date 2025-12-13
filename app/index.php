<?php
require_once("init.php");
require 'db.php';

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Igre App</title>
    <link rel="stylesheet" href="style.css">
    <style>
        nav a {
            margin-right: 15px;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .submenu {
            margin-left: 15px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<nav>
    <div class="left-links">
        <a href="?page=home">Home</a>
        <a href="?page=about">About</a>
        <a href="?page=leaderboard">Leaderboard</a>
        <a href="?page=feedback">Povratne informacije</a>
    </div>

    <div class="right-links">
        <?php if(isset($_SESSION['username'])): ?>
            Pozdravljen, <?= htmlspecialchars($_SESSION['username']) ?> |
            <a href="logout.php">Odjava</a>
        <?php else: ?>
            <a href="login.php">Prijava</a> | <a href="register.php">Registracija</a>
        <?php endif; ?>
    </div>
</nav>

<hr>

<div>
<?php
switch ($page) {
    case 'home':
        echo "<h1>Dobrodošel na naši igralni strani!</h1>";
        echo "<p>Tukaj lahko igraš igre in spremljaš lestvico najboljših.</p>";

        // igre
        echo '<div style="margin-top:30px;">';
        echo '<h2>Singleplayer igre</h2>';
        echo '<button onclick="location.href=\'?page=reakcija\'" style="margin:5px; padding:15px 30px; font-size:18px;">Reakcija</button>';
        echo '<button onclick="location.href=\'?page=clickcatcher\'" style="margin:5px; padding:15px 30px; font-size:18px;">Ujemi</button>';
        echo '<button onclick="location.href=\'?page=timergame\'" style="margin:5px; padding:15px 30px; font-size:18px;">Čas</button>';
        echo '</div>';

        echo '<div style="margin-top:30px;">';
        echo '<h2>Multiplayer igre</h2>';
        echo '<button onclick="location.href=\'?page=multiplayer\'" style="margin:5px; padding:15px 30px; font-size:18px;">Križci & Krožci</button>';
        echo '</div>';
    break;

    case 'about':
        echo "<h1>O aplikaciji</h1>";
        echo "<p>Preprosta spletna aplikacija za igranje iger.</p>";
        break;

    case 'feedback':
        include 'feedback.php';
        break;

    case 'leaderboard':
        include 'leaderboard.php';
        break;

    case 'reakcija':
        ?>
        <h1>Reakcija</h1>
        <p>Klikni gumb takoj, ko se pojavi. Merimo tvoj čas reakcije!</p>

        <button id="reactionButton" style="display:none; padding:20px; font-size:20px;">Klikni me!</button>
        <p id="info">Počakaj, da se gumb pojavi...</p>
        <p>Tvoj čas reakcije: <span id="reactionTime">-</span> ms</p>
        
        <script>const userLoggedIn = <?= isset($_SESSION['username']) ? 'true' : 'false' ?>;</script>
        <script src="js/reactionGame.js"></script>
        <?php
        break;

    case 'clickcatcher':
        ?>
        <h1>Ujemi kvadrate</h1>
        <p>Klikni kvadrat, preden izgine, čim večkrat v 15 sekundah!</p>
        <div id="catcher-game" style="position: relative; width: 400px; height: 300px; border: 1px solid #ccc; margin: 20px auto;">
        <div id="target" style="width: 50px; height: 50px; background: red; position: absolute; display: none; cursor: pointer;"></div>
        </div>

        <button id="startCatcherBtn">Začni ujemi kvadrat</button>
        <p>Točke: <span id="catcherScore">0</span></p>
        <p id="catcherMessage"></p>
        <p id="catcherLoginMessage" style="color:red; font-weight:bold;"></p>
        <button id="saveScoreBtn" style="margin-top: 10px;">Objavi rezultat na lestvico</button>
        
        <script>const userLoggedIn = <?= isset($_SESSION['username']) ? 'true' : 'false' ?>;</script>
        <script src="js/clickcatcherGame.js"></script>
        <?php
        break;

    case 'timergame':
        ?>
        <h1>Štetje sekund</h1>
        <p>Poskusi klikniti gumb točno po 5 sekundah!</p>
        <button id="startTimerBtn">Začni Štetje sekund</button>
        <button id="stopTimerBtn" disabled>Ustavi</button>
        <p>Tvoj čas: <span id="timerResult">-</span> ms</p>
        <p id="timerMessage"></p>
        <button id="saveScoreBtn" style="margin-top: 10px;">Objavi rezultat na lestvico</button>
        
        <script> const userLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;</script>
        <script src="js/timergame.js"></script>
        <?php
        break;

    case 'multiplayer':
        ?>
        <h1>Multiplayer - Križci & Krožci</h1>

        <div id="game" style="max-width: 320px; margin: 20px auto; text-align: center;">
            <table id="board" style="margin:auto; border-collapse: collapse; user-select:none;" border="1">
                <tr>
                    <td data-cell="0" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="1" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="2" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                </tr>
                <tr>
                    <td data-cell="3" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="4" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="5" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                </tr>
                <tr>
                    <td data-cell="6" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="7" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                    <td data-cell="8" style="width:100px; height:100px; font-size:70px; text-align:center; vertical-align: middle; cursor:pointer;"></td>
                </tr>
            </table>
            <p id="status" style="margin-top:15px; font-weight:bold; font-size:20px;"></p>
            <button id="resetBtn" style="margin-top:15px; padding:10px 20px; font-size:16px; cursor:pointer;">Ponastavi igro</button>
        </div>

        <script src="js/krizcikrozciGame.js"></script>
        <?php
        break;

    default:
        echo "<h1>Stran ni najdena</h1>";
}
?>
</div>

</body>
</html>