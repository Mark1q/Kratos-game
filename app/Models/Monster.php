<?php

namespace App\Models;

class Monster extends Fighter
{
    public function __construct()
    {
        $this->name      = 'Monster';
        $this->health    = rand(50, 80);
        $this->maxHealth = $this->health;
        $this->strength  = rand(55, 80);
        $this->defence   = rand(50, 70);
        $this->speed     = rand(40, 60);
        $this->luck      = rand(30, 45) / 100;
    }
}
