<?php

namespace Tests\Unit;

use App\Models\Kratos;
use App\Skills\MagicArmour;
use App\Skills\RapidFire;
use App\Skills\SkillInterface;
use PHPUnit\Framework\TestCase;

class KratosTest extends TestCase
{
    public function test_kratos_has_two_skills_by_default(): void
    {
        $k = new Kratos();
        $this->assertCount(2, $k->skills);
    }

    public function test_kratos_has_rapid_fire_skill(): void
    {
        $k     = new Kratos();
        $names = array_map(fn($s) => $s->getName(), $k->skills);
        $this->assertContains('Rapid Fire', $names);
    }

    public function test_kratos_has_magic_armour_skill(): void
    {
        $k     = new Kratos();
        $names = array_map(fn($s) => $s->getName(), $k->skills);
        $this->assertContains('Magic Armour', $names);
    }

    public function test_get_attack_skill_returns_skill_that_applies_on_attack(): void
    {
        $k = new Kratos();

        // Force a skill that always triggers
        $alwaysAttack = new class implements SkillInterface {
            public function getName(): string          { return 'AlwaysAttack'; }
            public function triggers(): bool           { return true; }
            public function appliesOnAttack(): bool    { return true; }
            public function appliesOnDefence(): bool   { return false; }
            public function modifyAttack(int $d): array { return [$d * 2]; }
            public function modifyDefence(int $d): int  { return $d; }
        };

        $k->skills = [$alwaysAttack];

        $skill = $k->getAttackSkill();
        $this->assertNotNull($skill);
        $this->assertSame('AlwaysAttack', $skill->getName());
    }

    public function test_get_attack_skill_returns_null_when_no_attack_skill_triggers(): void
    {
        $k = new Kratos();

        $neverAttack = new class implements SkillInterface {
            public function getName(): string          { return 'NeverAttack'; }
            public function triggers(): bool           { return false; }
            public function appliesOnAttack(): bool    { return true; }
            public function appliesOnDefence(): bool   { return false; }
            public function modifyAttack(int $d): array { return [$d]; }
            public function modifyDefence(int $d): int  { return $d; }
        };

        $k->skills = [$neverAttack];
        $this->assertNull($k->getAttackSkill());
    }

    public function test_get_defence_skill_returns_skill_that_applies_on_defence(): void
    {
        $k = new Kratos();

        $alwaysDefence = new class implements SkillInterface {
            public function getName(): string          { return 'AlwaysDefence'; }
            public function triggers(): bool           { return true; }
            public function appliesOnAttack(): bool    { return false; }
            public function appliesOnDefence(): bool   { return true; }
            public function modifyAttack(int $d): array { return [$d]; }
            public function modifyDefence(int $d): int  { return (int) ceil($d / 2); }
        };

        $k->skills = [$alwaysDefence];

        $skill = $k->getDefenceSkill();
        $this->assertNotNull($skill);
        $this->assertSame('AlwaysDefence', $skill->getName());
    }

    public function test_get_defence_skill_returns_null_when_no_defence_skill_triggers(): void
    {
        $k = new Kratos();

        $neverDefence = new class implements SkillInterface {
            public function getName(): string          { return 'NeverDefence'; }
            public function triggers(): bool           { return false; }
            public function appliesOnAttack(): bool    { return false; }
            public function appliesOnDefence(): bool   { return true; }
            public function modifyAttack(int $d): array { return [$d]; }
            public function modifyDefence(int $d): int  { return $d; }
        };

        $k->skills = [$neverDefence];
        $this->assertNull($k->getDefenceSkill());
    }

    public function test_get_attack_skill_skips_defence_only_skills(): void
    {
        $k = new Kratos();

        $defenceOnly = new class implements SkillInterface {
            public function getName(): string          { return 'DefenceOnly'; }
            public function triggers(): bool           { return true; }
            public function appliesOnAttack(): bool    { return false; }
            public function appliesOnDefence(): bool   { return true; }
            public function modifyAttack(int $d): array { return [$d]; }
            public function modifyDefence(int $d): int  { return $d; }
        };

        $k->skills = [$defenceOnly];
        $this->assertNull($k->getAttackSkill());
    }

    public function test_kratos_name_is_set(): void
    {
        $this->assertSame('Kratos', (new Kratos())->name);
    }
}
