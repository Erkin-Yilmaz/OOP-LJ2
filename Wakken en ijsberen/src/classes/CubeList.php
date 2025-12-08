<?php

class CubeList
{
    /** @var Cube[] */
    private array $cubes = [];

    public function addCube(Cube $cube): void
    {
        $this->cubes[] = $cube;
    }

    /**
     * @return Cube[]
     */
    public function getCubes(): array
    {
        return $this->cubes;
    }
}
