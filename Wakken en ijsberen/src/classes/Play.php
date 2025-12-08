<?php

//namespace Project5;

class Play
{
    private string $name;
    private GameList $gameList;
    private HintList $hintList;

    public function __construct()
    {
        $this->gameList = new GameList();
        $_SESSION['correct'] = 0;
        // hintlijst aanmaken
        $this->hintList = new HintList();
        $this->setHints();
    }

    public function reset()
    {
        if(isset($_SESSION['turn'])){
            unset($_SESSION['turn']);
        }
        if(isset($_SESSION['play'])){
            unset($_SESSION['play']);
        }
        if(isset($_SESSION['game'])){
            unset($_SESSION['game']);
        }
        if(isset($_SESSION['status'])){
            unset($_SESSION['status']);
        }
        if(isset($_SESSION['wrong'])){
            unset($_SESSION['wrong']);
        }
    }

    /*
    * Hints aanmaken voor elke page reload
    */
    public function setHints()
    {
        $hints = [
            'Wakken bevinden zich in het noorden van de Noordpool',
            'Op de Noordpool kan je de wakken in het midden vinden',
            'Ijsberen kan je vinden op de Noordpool',
            'Ijsberen bevinden zich alleen rondom de wakken',
            'Als er een wak is op de Noordpool, zijn er pinguins op de Zuidpool'
        ];

        foreach($hints as $hint)
        {
            $hint = new Hint($hint);
            $this->hintList->addHint($hint);
        }
    }

    public function setPlayerName($name)
    {
        $this->name = $name;
    }

    public function getPlayerName(): string
    {
        return $this->name;
    }

    public function addGame($amount)
    {
        $this->gameList->addGame($amount);
    }

    public function addGuess($iceHoles, $polarBears, $penguins)
    {
        return $this->gameList->getCurrentGame()->addGuess($iceHoles, $polarBears, $penguins);
    }

    public function checkScore()
    {
        return $this->gameList->getCurrentGame()->checkGuess();
    }

    public function draw()
    {
        return $this->gameList->getCurrentGame()->drawCubes();
    }

    public function getHint()
    {
        return $this->hintList->getRandomHint()->getHintString();
    }

    public function getPreviousGames()
    {
        // $output = array_slice($input, 0, 3);   // returns "a", "b", and "c"
        $games = array_slice($this->gameList->getGames(), 0, count($this->gameList->getGames())-1);
        return $games;
    }

    public function getAnswer()
    {
        return $this->gameList->getCurrentGame()->getAnswer();
    }

    public function getScore(): int
    {
        $score = 0;
        $games =  $this->gameList->getGames();
        foreach($games as $game)
        {
            $score += $game->getScore();
        }
        return $score;
    }
}