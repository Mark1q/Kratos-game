<?php

namespace App\Skills;

class MagicArmour implements SkillInterface
{
    private float $chance = 0.15;

    public function getName(): string
    {
        return 'Magic Armour';
    }

    public function triggers(): bool
    {
        return (mt_rand(1, 100) / 100) <= $this->chance;
    }

    public function appliesOnAttack(): bool
    {
        return false;
    }

    public function appliesOnDefence(): bool
    {
        return true;
    }

    public function modifyAttack(int $baseDamage): array
    {
        return [$baseDamage]; // not an attack skill
    }

    /** Halves the incoming damage */
    public function modifyDefence(int $incomingDamage): int
    {
        return (int) ceil($incomingDamage / 2);
    }
}
