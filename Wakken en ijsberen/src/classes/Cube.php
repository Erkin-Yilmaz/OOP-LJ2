<?php

class Cube
{
    private int $cubeNr; // een bepaalde dobbelsteen
    private int $dice; // welke worp
    private int $iceHoles;
    private int $polarBears;
    private int $penguins;

    public function dice()
    {
        $this->dice = rand(1,6);

        // bepalen wakken, ijsberen en penguins
        if($this->dice === 1 || $this->dice === 3 || $this->dice === 5)
        {
            $this->iceHoles = 1;
            $this->polarBears = $this->dice -1;
            $this->penguins = 7 - $this->dice;
        }else{
            $this->iceHoles = $this->polarBears = $this->penguins = 0;
        }
    }

    public function getDice(): int
    {
        return $this->dice;
    }

    /**
     * @return int
     */
    public function getIceHoles(): int
    {
        return $this->iceHoles;
    }

    /**
     * @return int
     */
    public function getPolarBears(): int
    {
        return $this->polarBears;
    }

    /**
     * @return int
     */
    public function getPenguins(): int
    {
        return $this->penguins;
    }

    public function draw()
    {
        $draw = "<svg width='180' height='180'>
            <rect x='20' y='20' rx='20' ry='20' width='150' height='150'
                  style='fill:lightblue;stroke:black;stroke-width:5;opacity:1'  />";

        if ($this->dice === 1 || $this->dice === 3 || $this->dice === 5) {
            //midden
            $draw .= "<svg width='180' height='180'>
                        <circle cx='95' cy='95' r='10' stroke='blue' stroke-width='1' fill='blue' />
                        </svg>";
        }
        if ($this->dice === 4 || $this->dice === 5 || $this->dice === 6) {
            // linkstop, rechtsbot
            $draw .= "<svg width='180' height='180'>
                <circle cx='55' cy='55' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>
            <svg width='180' height='180'>
                <circle cx='135' cy='135' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>";
        }
        if ($this->dice === 6)
        {
            // linksmid, rechtsmid
            $draw .= "<svg width='180' height='180'>
                <circle cx='55' cy='95' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>
            <svg width='180' height='180'>
                <circle cx='135' cy='95' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>";
        }
        if($this->dice === 2 || $this->dice === 3 || $this->dice === 4 || $this->dice === 5 || $this->dice === 6)
        {
            //linksbot, rechtstop
            $draw .= "<svg width='180' height='180'>
                <circle cx='135' cy='55' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>
            <svg width='180' height='180'>
                <circle cx='55' cy='135' r='10' stroke='white' stroke-width='1' fill='white' />
            </svg>";
        }
        $draw .= '</svg>';

        return $draw;
    }
}