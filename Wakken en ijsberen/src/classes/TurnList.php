<?php

class TurnList
{
    /** @var Turn[] */
    private array $turns = [];

    public function addTurn(Turn $turn): void
    {
        $this->turns[] = $turn;
    }

    /**
     * @return Turn
     */
    public function getCurrentTurn(): Turn
    {
        if (empty($this->turns)) {
            throw new Exception('No turns available');
        }
        return $this->turns[count($this->turns) - 1];
    }

    public function getAmountTurns(): int
    {
        return count($this->turns);
    }

    /** @return Turn[] */
    public function getTurns(): array
    {
        return $this->turns;
    }
}
