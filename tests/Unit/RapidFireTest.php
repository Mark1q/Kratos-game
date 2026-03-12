<?php

namespace Tests\Unit;

use App\Skills\RapidFire;
use PHPUnit\Framework\TestCase;

class RapidFireTest extends TestCase
{
    private RapidFire $skill;

    protected function setUp(): void
    {
        $this->skill = new RapidFire();
    }

    public function test_name_is_rapid_fire(): void
    {
        $this->assertSame('Rapid Fire', $this->skill->getName());
    }

    public function test_applies_on_attack(): void
    {
        $this->assertTrue($this->skill->appliesOnAttack());
    }

    public function test_does_not_apply_on_defence(): void
    {
        $this->assertFalse($this->skill->appliesOnDefence());
    }

    public function test_modify_attack_returns_two_hits_of_same_damage(): void
    {
        $hits = $this->skill->modifyAttack(20);

        $this->assertCount(2, $hits);
        $this->assertSame(20, $hits[0]);
        $this->assertSame(20, $hits[1]);
    }

    public function test_modify_attack_with_zero_damage(): void
    {
        $hits = $this->skill->modifyAttack(0);

        $this->assertCount(2, $hits);
        $this->assertSame(0, $hits[0]);
        $this->assertSame(0, $hits[1]);
    }

    public function test_modify_defence_is_a_passthrough(): void
    {
        $this->assertSame(35, $this->skill->modifyDefence(35));
    }

    public function test_triggers_returns_bool(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $this->assertIsBool($this->skill->triggers());
        }
    }

    public function test_triggers_eventually_returns_true(): void
    {
        $triggered = false;
        for ($i = 0; $i < 200; $i++) {
            if ($this->skill->triggers()) {
                $triggered = true;
                break;
            }
        }
        $this->assertTrue($triggered, 'RapidFire should trigger at least once in 200 attempts');
    }

    public function test_triggers_eventually_returns_false(): void
    {
        $notTriggered = false;
        for ($i = 0; $i < 200; $i++) {
            if (! $this->skill->triggers()) {
                $notTriggered = true;
                break;
            }
        }
        $this->assertTrue($notTriggered, 'RapidFire should NOT trigger at least once in 200 attempts');
    }
}
