<?php
include ("classes/Cube.php");
include ("classes/CubeList.php");
include ("classes/Game.php");
include ("classes/GameList.php");
include ("classes/Hint.php");
include ("classes/HintList.php");
include ("classes/Turn.php");
include ("classes/TurnList.php");
include ("classes/Play.php");

session_start();

// start new Game
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.87.0">
    <title>Sticky Footer Navbar Template · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sticky-footer-navbar/">



    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">


    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.1/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.1/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.1/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.1/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        /* Custom page CSS
-------------------------------------------------- */
        /* Not required for template or sticky footer method. */

        main > .container {
            padding: 60px 15px 0;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="sticky-footer-navbar.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Wakken, Ijsberen en Pinguins</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <form action="" method="post">
                            <button type="submit" name="newGame" value="newGame" class="btn btn-primary">Start new Game</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main class="flex-shrink-0">
    <div class="container">
        <h1> </h1>
        <div class="alert alert-secondary" role="alert">
            De bedoeling van Wakken, Ijsberen en Pinguins is: <br>
            Dat je er achter moet komen hoe het spel werkt.
            Dit doe je door middel van raden, goed nadenken en lezen van de hints als je het fout hebt geraden.
            Je kan tevens het antwoord krijgen van een game, maar dan is de game wel meteen voorbij en kan je de volgende game starten.
        </div>
        <!-- CONTENT -->

<?php

if(isset($_SESSION['play'])){
    $play = $_SESSION['play'];
}

    // clear everything like scores etc
    // Fill in your name
    if(isset($_POST['newGame']))
    {
        $play = new Play();
        $play->reset();
        $_SESSION['play'] = $play;
        ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Naam Speler</label>
                <input name="name" type="text" id="name" class="form-control">
            </div>
            <button type="submit" name="newPlay" value="newPlay" class="btn btn-primary">Play</button>
        </form>
        <?php
    }
if(isset($play)){
    if (isset($_POST['guess']) && is_numeric($_POST['iceholes']) && is_numeric($_POST['polarbears']) && is_numeric($_POST['penguins'])) {
        $play->addGuess($_POST['iceholes'], $_POST['polarbears'], $_POST['penguins']);

        $score = $play->checkScore();
        if($score == 'helaas fout')
        {
            echo "<div class='alert alert-danger' role='alert'>".$score."</div>";
        }else{
            echo "<div class='alert alert-success' role='alert'>".$score."</div>";
        }
    }elseif(isset($_POST['answer'])){
        $play->draw();
        ?>
        <h3>Antwoord</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Aantal Wakken</th>
                    <th scope="col">Aantal Ijsberen</th>
                    <th scope="col">Aantal Pinguins</th>
                </tr>
            </thead>
            <tbody>
                <tr scope="row">
        <?php
        foreach($play->getAnswer() as $answer)
        {
            echo "<td>$answer</td>";
        }
        echo '</tr>
            </tbody>';
    }elseif(isset($_POST['cubes']))
    {
        // aanmaken nieuwe game
        $game = new Game($_POST['cubes']);
        // registeren voor deze speler
        $play->addGame($game);
    }

    // Start new Play
    if(isset($_POST['newPlay']) || (isset($_SESSION['status']) && ($_SESSION['status'] == 'correct' || $_SESSION['status'] == 'answer')))
    {
        // save name in Session & object
        if(!empty($_POST['name'])){
            $play->setPlayerName($_POST['name']);
        }

        ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="cubes" class="form-label">Aantal dobbelstenen</label>
                <input name="cubes" type="number" min="3" max="8"  id="cubes" class="form-control">
            </div>
            <button type="submit" name="Submit" class="btn btn-primary">Gooi Dobbelstenen</button>
        </form>
        <?php
    }

    if(isset($_SESSION['status']) && ($_SESSION['status'] == 'start' || $_SESSION['status'] == 'wrong'))
    {
        if($_SESSION['status'] == 'wrong')
        {
            // hint laten zien
            echo "<div class='alert alert-info' role='alert'>Hint: ".$play->getHint()."</div>";
        }
        $play->draw();
        ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="iceholes" class="form-label">Raad Wakken</label>
                <input name="iceholes" type="number" id="iceholes" class="form-control">
            </div>
            <div class="mb-3">
                <label for="polarbears" class="form-label">Raad Ijsberen</label>
                <input name="polarbears" type="number" id="polarbears" class="form-control">
            </div>
            <div class="mb-3">
                <label for="penguins" class="form-label">Raad Pinguins</label>
                <input name="penguins" type="number" id="penguins" class="form-control">
            </div>
            <div class="mb-3">
                <button type="submit" name="guess" value="guess" class="btn btn-primary">Raad</button>
                <button type="submit" name="answer" value="answer" class="btn btn-primary">Geef oplossing</button>
            </div>
        </form>
        <?php
    }


    ?>
    <h3>Huidige game</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Fout geraden</td>
            </tr>
        </thead>
        <tbody>
            <tr scope="row">
                <td><?php if(isset($_SESSION['wrong']))
                    {
                        echo $_SESSION['wrong'];
                    } ?></td>
            </tr>
        </tbody>
    </table>
    <h3>Vorige games</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Aantal beurten</th>
            <th scope="col">Fout geraden</th>
        </tr>
        </thead>
        <tbody>
            <?php

                foreach($play->getPreviousGames() as $game)
                {
                    ?>
                    <tr scope="row">
                        <td>Game</td>
                        <td><?php echo $game->getGameTurns(); ?></td>
                        <td><?php echo $game->getWrongAnswers() ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3">Score: <?php echo $play->getScore(); ?></td>
                </tr>

        </tbody>
    </table>
<?php

}

print '<pre>';
//var_dump($play);
//var_dump($_SESSION);
?>


        <!-- END CONTENT -->
    </div>
</main>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
    </div>
</footer>


<script src="/docs/5.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>


</body>
</html>





