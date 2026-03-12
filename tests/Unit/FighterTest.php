<?php

namespace Tests\Unit;

use App\Models\Kratos;
use App\Models\Monster;
use PHPUnit\Framework\TestCase;

class FighterTest extends TestCase
{

    public function test_fighter_is_alive_when_health_is_positive(): void
    {
        $kratos         = new Kratos();
        $kratos->health = 50;
        $this->assertTrue($kratos->isAlive());
    }

    public function test_fighter_is_not_alive_when_health_is_zero(): void
    {
        $kratos         = new Kratos();
        $kratos->health = 0;
        $this->assertFalse($kratos->isAlive());
    }

    public function test_take_damage_reduces_health_correctly(): void
    {
        $kratos         = new Kratos();
        $kratos->health = 100;
        $kratos->takeDamage(30);
        $this->assertSame(70, $kratos->health);
    }

    public function test_take_damage_does_not_go_below_zero(): void
    {
        $monster         = new Monster();
        $monster->health = 10;
        $monster->takeDamage(999);
        $this->assertSame(0, $monster->health);
    }

    public function test_take_damage_of_zero_leaves_health_unchanged(): void
    {
        $kratos         = new Kratos();
        $kratos->health = 60;
        $kratos->takeDamage(0);
        $this->assertSame(60, $kratos->health);
    }

    public function test_kratos_stats_are_within_expected_ranges(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $k = new Kratos();
            $this->assertGreaterThanOrEqual(65, $k->health);
            $this->assertLessThanOrEqual(100, $k->health);
            $this->assertGreaterThanOrEqual(75, $k->strength);
            $this->assertLessThanOrEqual(90, $k->strength);
            $this->assertGreaterThanOrEqual(40, $k->defence);
            $this->assertLessThanOrEqual(50, $k->defence);
            $this->assertGreaterThanOrEqual(40, $k->speed);
            $this->assertLessThanOrEqual(50, $k->speed);
            $this->assertGreaterThanOrEqual(0.10, $k->luck);
            $this->assertLessThanOrEqual(0.20, $k->luck);
        }
    }

    public function test_monster_stats_are_within_expected_ranges(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $m = new Monster();
            $this->assertGreaterThanOrEqual(50, $m->health);
            $this->assertLessThanOrEqual(80, $m->health);
            $this->assertGreaterThanOrEqual(55, $m->strength);
            $this->assertLessThanOrEqual(80, $m->strength);
            $this->assertGreaterThanOrEqual(50, $m->defence);
            $this->assertLessThanOrEqual(70, $m->defence);
            $this->assertGreaterThanOrEqual(40, $m->speed);
            $this->assertLessThanOrEqual(60, $m->speed);
            $this->assertGreaterThanOrEqual(0.30, $m->luck);
            $this->assertLessThanOrEqual(0.45, $m->luck);
        }
    }

    public function test_kratos_max_health_equals_initial_health(): void
    {
        $k = new Kratos();
        $this->assertSame($k->maxHealth, $k->health);
    }

    public function test_monster_max_health_equals_initial_health(): void
    {
        $m = new Monster();
        $this->assertSame($m->maxHealth, $m->health);
    }

    public function test_to_array_contains_expected_keys(): void
    {
        $k    = new Kratos();
        $data = $k->toArray();

        foreach (['name', 'health', 'strength', 'defence', 'speed', 'luck'] as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }

    public function test_to_array_name_is_correct(): void
    {
        $this->assertSame('Kratos',  (new Kratos())->toArray()['name']);
        $this->assertSame('Monster', (new Monster())->toArray()['name']);
    }
}
