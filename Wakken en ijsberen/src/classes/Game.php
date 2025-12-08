<?php
class Game
{
    private int $wrong = 0;
    private int $correct = 0;
    private TurnList $turnList;
    private CubeList $cubeList;
    private int $resultIceHoles = 0;
    private int $resultPolarBears = 0;
    private int $resultPenguins = 0;

    // aantal dobbelstenen = amount.
    // per dobbelsteen nieuwe Cube aanmaken. Deze gooien met random waarde
    // Daarna dobbelsteen in de cubeList zetten
    // Dan mogen we de cubes tekenen op het scherm.
    // status start (kan veranderen in: wrong = niet geraden, correct = geraden, answer = antwoord gegeven)
    public function __construct($amount)
    {
        $_SESSION['status'] = 'start';
        // dobbelstenen aanmaken
        $this->cubeList = new CubeList();
        for($i = $amount; $i > 0; $i--)
        {
            $$i = new Cube();
            $$i->dice();
            $this->cubeList->addCube($$i);
        }
        // dobbelstenen tekenen
        //$this->drawCubes();

        // resultaat berekenen.
        $this->result();
        // turnlist aanmaken
        $this->turnList = new TurnList();
        $_SESSION['turn'] = 0;
        $_SESSION['wrong'] = 0;
    }

    // toon de dobbelstenen op het scherm
    public function drawCubes()
    {
        foreach($this->cubeList->getCubes() as $cube)
        {
            echo $cube->draw();
        }
    }

    // bereken totaal aantal wakken, ijsberen en pinguins
    public function result()
    {
        foreach($this->cubeList->getCubes() as $cube)
        {
            $this->resultIceHoles += $cube->getIceHoles();
            $this->resultPolarBears += $cube->getPolarBears();
            $this->resultPenguins += $cube->getPenguins();
        }
    }

    /**
     * @return TurnList
     */
    public function getTurnList(): TurnList
    {
        return $this->turnList;
    }

    public function addGuess($iceHoles, $polarBears, $penguins)
    {
        $turn = new Turn($iceHoles, $polarBears, $penguins);
        $this->turnList->addTurn($turn);
        return $this->turnList->getCurrentTurn();
    }

    public function checkGuess(): string
    {
        $turn = $this->turnList->getCurrentTurn();
        if($turn->getGuessIceHoles() === $this->resultIceHoles && $turn->getGuessPolarBears() === $this->resultPolarBears && $turn->getGuessPenguins() === $this->resultPenguins)
        {
            $_SESSION['status'] = 'correct';
            $_SESSION['correct']++;
            $this->correct++;
            return 'correct geraden';
        }else{
            $_SESSION['status'] = 'wrong';
            $_SESSION['wrong']++;
            $this->wrong++;
            return 'helaas fout';
        }
    }

    public function getGameTurns()
    {
        return $this->turnList->getAmountTurns();
    }

    public function getWrongAnswers(): int
    {
        return $this->wrong;
    }

    public function getAnswer(): array
    {
        $_SESSION['status'] = 'answer';
        return [$this->resultIceHoles, $this->resultPolarBears, $this->resultPenguins];
    }

    public function getScore(): int
    {
        return $this->correct;
    }

}