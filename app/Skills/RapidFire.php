<?php

namespace App\Skills;

class RapidFire implements SkillInterface
{
    private float $chance = 0.15;

    public function getName(): string
    {
        return 'Rapid Fire';
    }

    public function triggers(): bool
    {
        return (mt_rand(1, 100) / 100) <= $this->chance;
    }

    public function appliesOnAttack(): bool
    {
        return true;
    }

    public function appliesOnDefence(): bool
    {
        return false;
    }

    /** Returns two hits instead of one */
    public function modifyAttack(int $baseDamage): array
    {
        return [$baseDamage, $baseDamage];
    }

    public function modifyDefence(int $incomingDamage): int
    {
        return $incomingDamage; // not a defence skill
    }
}
