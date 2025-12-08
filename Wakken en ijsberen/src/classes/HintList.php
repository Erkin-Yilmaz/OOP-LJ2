<?php

class HintList
{
    /** @var Hint[] */
    private array $hints = [];

    public function addHint(Hint $hint): void
    {
        $this->hints[] = $hint;
    }

    public function getRandomHint(): Hint
    {
        if (empty($this->hints)) {
            throw new Exception('No hints available');
        }
        $index = array_rand($this->hints);
        return $this->hints[$index];
    }

    /** @return Hint[] */
    public function getHints(): array
    {
        return $this->hints;
    }
}
