<?php
class Turn
{
    private int $guessIceHoles = 0;
    private int $guessPolarBears = 0;
    private int $guessPenguins = 0;

    public function __construct(int $iceHoles, int $polarBears, int $penguins)
    {
        $this->guessIceHoles = $iceHoles;
        $this->guessPolarBears = $polarBears;
        $this->guessPenguins = $penguins;

        if (!isset($_SESSION['turn'])) {
            $_SESSION['turn'] = 0;
        }
        $_SESSION['turn']++;
    }

    /**
     * @return int
     */
    public function getGuessIceHoles(): int
    {
        return $this->guessIceHoles;
    }

    /**
     * @return int
     */
    public function getGuessPolarBears(): int
    {
        return $this->guessPolarBears;
    }

    /**
     * @return int
     */
    public function getGuessPenguins(): int
    {
        return $this->guessPenguins;
    }

    // controleer het ingevulde resultaat
    /**
     * Compare this turn's guess against the actual results.
     *
     * @param int $resIceHoles
     * @param int $resPolarBears
     * @param int $resPenguins
     * @return bool True when the guess matches the results
     */
    public function check(int $resIceHoles, int $resPolarBears, int $resPenguins): bool
    {
        return $this->guessIceHoles === $resIceHoles
            && $this->guessPolarBears === $resPolarBears
            && $this->guessPenguins === $resPenguins;
    }
}