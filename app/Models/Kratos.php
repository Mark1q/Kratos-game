<?php

namespace App\Models;

use App\Skills\MagicArmour;
use App\Skills\RapidFire;
use App\Skills\SkillInterface;

class Kratos extends Fighter
{
    /** @var SkillInterface[] */
    public array $skills = [];

    public function __construct()
    {
        $this->name     = 'Kratos';
        $this->health   = rand(65, 100);
        $this->maxHealth = $this->health;
        $this->strength = rand(75, 90);
        $this->defence  = rand(40, 50);
        $this->speed    = rand(40, 50);
        $this->luck     = rand(10, 20) / 100;

        $this->skills = [
            new RapidFire(),
            new MagicArmour(),
        ];
    }

    public function getAttackSkill(): ?SkillInterface
    {
        foreach ($this->skills as $skill) {
            if ($skill->appliesOnAttack() && $skill->triggers()) {
                return $skill;
            }
        }
        return null;
    }

    public function getDefenceSkill(): ?SkillInterface
    {
        foreach ($this->skills as $skill) {
            if ($skill->appliesOnDefence() && $skill->triggers()) {
                return $skill;
            }
        }
        return null;
    }
}
