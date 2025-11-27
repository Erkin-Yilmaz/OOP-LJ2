<?php
require_once 'Dice.php';

class Player
{
    private string $name;

  
    private array $dice = [];

    private int $score = 0;

    public function __construct(string $name, int $numDice = 5)
    {
        $this->name = $name;

        for ($i = 0; $i < $numDice; $i++) {
            $this->dice[] = new Dice();
        }
    }

    public function throwAllDice(): array
    {
        foreach ($this->dice as $die) {
            $die->throwDice();
        }
        return $this->getDiceValues();
    }

    public function getDiceValues(): array
    {
        return array_map(fn(Dice $d) => $d->getFaceValue(), $this->dice);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addScore(int $points): void
    {
        $this->score += $points;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
