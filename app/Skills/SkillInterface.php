<?php

namespace App\Skills;

interface SkillInterface
{
    public function getName(): string;
    public function triggers(): bool;
    public function appliesOnAttack(): bool;
    public function appliesOnDefence(): bool;
    public function modifyAttack(int $baseDamage): array;
    public function modifyDefence(int $incomingDamage): int;
}
