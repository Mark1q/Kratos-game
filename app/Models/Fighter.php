<?php

namespace App\Models;

abstract class Fighter
{
    public string $name;
    public int $health;
    public int $maxHealth;
    public int $strength;
    public int $defence;
    public int $speed;
    public float $luck; // 0.0 to 1.0

    public function isAlive(): bool
    {
        return $this->health > 0;
    }

    public function takeDamage(int $damage): void
    {
        $this->health = max(0, $this->health - $damage);
    }

    public function isLucky(): bool
    {
        return (mt_rand(1, 100) / 100) <= $this->luck;
    }

    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'health'   => $this->health,
            'strength' => $this->strength,
            'defence'  => $this->defence,
            'speed'    => $this->speed,
            'luck'     => $this->luck,
        ];
    }
}
