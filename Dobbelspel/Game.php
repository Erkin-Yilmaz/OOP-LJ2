<?php
require_once 'Player.php';

class Game
{
    
    private array $players = [];

    private int $currentPlayerIndex = 0;
    private int $throwCount = 0;        
    private array $history = [];        

    public function __construct(int $numPlayers = 1)
    {
        $numPlayers = max(1, min(2, $numPlayers)); 

        for ($i = 1; $i <= $numPlayers; $i++) {
            $this->players[] = new Player("Speler $i");
        }
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getCurrentPlayer(): Player
    {
        return $this->players[$this->currentPlayerIndex];
    }

    public function getThrowCount(): int
    {
        return $this->throwCount;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function getCurrentPlayerIndex(): int
    {
        return $this->currentPlayerIndex;
    }

    public function maxThrowsReached(): bool
    {
        return $this->throwCount >= 3;
    }

 
    public function play(): ?array
    {
        if ($this->maxThrowsReached()) {
            
            return null;
        }

        $player = $this->getCurrentPlayer();
        $values = $player->throwAllDice();
        $this->throwCount++;

        
        $bonus = $this->calculateBonus($values);
        $baseScore = array_sum($values);
        $player->addScore($baseScore + $bonus);

       
        $specialMessage = $this->getSpecialMessage($values, $bonus);

        $record = [
            'player'  => $player->getName(),
            'throw'   => $this->throwCount,
            'values'  => $values,
            'bonus'   => $bonus,
            'total'   => $baseScore + $bonus,
            'special' => $specialMessage
        ];

        $this->history[] = $record;

        
        if ($this->throwCount >= 3) {
            $this->throwCount = 0;
            $this->currentPlayerIndex = ($this->currentPlayerIndex + 1) % count($this->players);
        }

        return $record;
    }

    private function calculateBonus(array $values): int
    {
        
        $counts = array_count_values($values);
        $bonus  = 0;

        if (in_array(5, $counts, true)) {          
            $bonus += 50;
        } elseif (in_array(4, $counts, true)) {   
            $bonus += 25;
        } elseif (in_array(3, $counts, true)) {    
            $bonus += 10;
        }

        return $bonus;
    }

    private function getSpecialMessage(array $values, int $bonus): string
    {
        $counts = array_count_values($values);

        
        if (in_array(5, $counts, true)) {
            return "🎉 Alle dobbelstenen tonen hetzelfde! (+$bonus bonus)";
        }

        if ($bonus > 0) {
            return "Mooie worp, je krijgt $bonus bonuspunten.";
        }

        return "";
    }
}
