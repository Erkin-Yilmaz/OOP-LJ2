<?php

class GameList
{
    /** @var Game[] */
    private array $games = [];

    public function addGame(Game $game): void
    {
        $this->games[] = $game;
    }

    /**
     * @return Game
     */
    public function getCurrentGame(): Game
    {
        if (empty($this->games)) {
            throw new Exception('No games available');
        }
        return $this->games[count($this->games) - 1];
    }

    /**
     * @return Game[]
     */
    public function getGames(): array
    {
        return $this->games;
    }
}
