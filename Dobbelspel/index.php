<?php
session_start();

require_once 'Game.php';


function genereerSvgVoorWorp(int $waarde, bool $highlight = false): string
{
    $kleur = $highlight ? '#ffeb99' : 'white';

    $svg = "<svg width='60' height='60' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' style='margin:5px; border: 1px solid #000;'>";
    $svg .= "<rect width='100' height='100' style='fill: {$kleur};'/>";

    $ogenPosities = [
        1 => [[50, 50]],
        2 => [[30, 30], [70, 70]],
        3 => [[30, 30], [50, 50], [70, 70]],
        4 => [[30, 30], [30, 70], [70, 30], [70, 70]],
        5 => [[30, 30], [30, 70], [50, 50], [70, 30], [70, 70]],
        6 => [[30, 30], [30, 50], [30, 70], [70, 30], [70, 50], [70, 70]],
    ];

    foreach ($ogenPosities[$waarde] as $positie) {
        $svg .= "<circle cx='{$positie[0]}' cy='{$positie[1]}' r='8' fill='black'/>";
    }

    $svg .= "</svg>";
    return $svg;
}


function getGame(): Game
{
    if (!isset($_SESSION['game'])) {
        // Default: 1 speler
        $_SESSION['game'] = serialize(new Game(1));
    }

    return unserialize($_SESSION['game']);
}


function saveGame(Game $game): void
{
    $_SESSION['game'] = serialize($game);
}

$lastThrow = null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'new_game') {
        $numPlayers = isset($_POST['players']) ? (int)$_POST['players'] : 1;
        $game = new Game($numPlayers);
        saveGame($game);
        $message = "Nieuw spel gestart met $numPlayers speler(s).";

    } elseif (isset($_POST['action']) && $_POST['action'] === 'throw') {
        $game = getGame();

        if ($game->maxThrowsReached()) {
            $message = "Je hebt al 3 keer gegooid. Start een nieuwe beurt of een nieuw spel.";
        } else {
            $lastThrow = $game->play();
            saveGame($game);
            if ($lastThrow === null) {
                $message = "Geen worp mogelijk.";
            }
        }
    }

} else {
    $game = getGame();
}


if (!isset($game)) {
    $game = getGame();
}

$players = $game->getPlayers();
$history = $game->getHistory();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dobbelspel OOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .dice-row {
            display: flex;
            align-items: center;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            background: #f0f0f0;
            border-left: 4px solid #333;
        }
        .scoreboard {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .scoreboard th, .scoreboard td {
            border: 1px solid #ccc;
            padding: 5px 8px;
            text-align: center;
        }
        .scoreboard th {
            background: #eee;
        }
        .player-scores {
            margin-top: 10px;
        }
        .player-scores div {
            margin-bottom: 5px;
        }
        .controls {
            margin: 15px 0;
        }
        .controls form {
            display: inline-block;
            margin-right: 15px;
        }
    </style>
</head>
<body>

<h1>Dobbelspel</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="player-scores">
    <h2>Spelers & Scores</h2>
    <?php foreach ($players as $index => $player): ?>
        <div>
            <strong><?= htmlspecialchars($player->getName()) ?></strong>
            — Score: <?= $player->getScore() ?>
            <?php if ($index === $game->getCurrentPlayerIndex() && !$game->maxThrowsReached()): ?>
                (Aan de beurt, worp <?= $game->getThrowCount() + 1 ?>/3)
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="controls">
   
    <form method="post">
        <input type="hidden" name="action" value="throw">
        <button type="submit">Gooi dobbelstenen</button>
    </form>

    <form method="post">
        <input type="hidden" name="action" value="new_game">
        <label>
            Aantal spelers:
            <select name="players">
                <option value="1">1 speler</option>
                <option value="2">2 spelers</option>
            </select>
        </label>
        <button type="submit">Nieuw spel</button>
    </form>
</div>

<?php
// Laat de laatste worp zien (dobbelstenen in SVG)
if ($lastThrow && isset($lastThrow['values'])) {
    $values = $lastThrow['values'];
    $counts = array_count_values($values);
    $maxCount = max($counts); 

    echo "<h2>Laatste worp van {$lastThrow['player']} (worp {$lastThrow['throw']})</h2>";
    echo "<div class='dice-row'>";
    foreach ($values as $value) {
       
        $highlight = ($counts[$value] === $maxCount && $maxCount > 1);
        echo genereerSvgVoorWorp($value, $highlight);
    }
    echo "</div>";

    echo "<p>Som ogen: " . array_sum($values) . "</p>";
    if ($lastThrow['bonus'] > 0) {
        echo "<p>Bonus: {$lastThrow['bonus']}</p>";
    }
    echo "<p>Totaal voor deze worp: {$lastThrow['total']}</p>";

    if (!empty($lastThrow['special'])) {
        echo "<p><strong>{$lastThrow['special']}</strong></p>";
    }
}
?>

<h2>Scorebord (Geschiedenis)</h2>
<table class="scoreboard">
    <thead>
    <tr>
        <th>Speler</th>
        <th>Worp</th>
        <th>Dobbelstenen</th>
        <th>Bonus</th>
        <th>Totaal worp</th>
        <th>Speciaal</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($history)): ?>
        <tr><td colspan="6">Nog geen worpen gedaan.</td></tr>
    <?php else: ?>
        <?php foreach ($history as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['player']) ?></td>
                <td><?= $row['throw'] ?></td>
                <td><?= implode(', ', $row['values']) ?></td>
                <td><?= $row['bonus'] ?></td>
                <td><?= $row['total'] ?></td>
                <td><?= htmlspecialchars($row['special']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
