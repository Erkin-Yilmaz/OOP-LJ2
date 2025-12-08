<?php

class Hint
{
    private string $hintString;

    public function __construct(string $hint)
    {
        $this->hintString = $hint;
    }

    public function getHintString(): string
    {
        return $this->hintString;
    }
}
