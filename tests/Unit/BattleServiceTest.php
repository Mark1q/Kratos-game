<?php

namespace Tests\Unit;

use App\Models\Kratos;
use App\Models\Monster;
use App\Services\BattleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_run_returns_expected_keys(): void
    {
        $result = (new BattleService())->run();

        foreach (['battle_id', 'kratos', 'monster', 'turns', 'winner', 'total_turns'] as $key) {
            $this->assertArrayHasKey($key, $result);
        }
    }

    public function test_run_winner_is_one_of_valid_values(): void
    {
        $result = (new BattleService())->run();
        $this->assertContains($result['winner'], ['Kratos', 'Monster', 'Draw']);
    }

    public function test_run_total_turns_is_at_least_one(): void
    {
        $result = (new BattleService())->run();
        $this->assertGreaterThanOrEqual(1, $result['total_turns']);
    }

    public function test_run_total_turns_does_not_exceed_max(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $result = (new BattleService())->run();
            $this->assertLessThanOrEqual(15, $result['total_turns']);
        }
    }

    public function test_run_turns_array_length_matches_total_turns(): void
    {
        $result = (new BattleService())->run();
        $this->assertCount($result['total_turns'], $result['turns']);
    }

    public function test_each_turn_has_expected_keys(): void
    {
        $result = (new BattleService())->run();

        foreach ($result['turns'] as $turn) {
            foreach (['turn', 'attacker', 'defender', 'skill_used', 'lucky_miss', 'damage_dealt', 'defender_health_remaining'] as $key) {
                $this->assertArrayHasKey($key, $turn);
            }
        }
    }

    public function test_attacker_in_each_turn_is_kratos_or_monster(): void
    {
        $result = (new BattleService())->run();

        foreach ($result['turns'] as $turn) {
            $this->assertContains($turn['attacker'], ['Kratos', 'Monster']);
        }
    }

    public function test_damage_dealt_is_non_negative_in_all_turns(): void
    {
        $result = (new BattleService())->run();

        foreach ($result['turns'] as $turn) {
            $this->assertGreaterThanOrEqual(0, $turn['damage_dealt']);
        }
    }

    public function test_defender_health_remaining_is_non_negative(): void
    {
        $result = (new BattleService())->run();

        foreach ($result['turns'] as $turn) {
            $this->assertGreaterThanOrEqual(0, $turn['defender_health_remaining']);
        }
    }

    public function test_lucky_miss_turns_deal_zero_damage(): void
    {
        // Run many battles to catch lucky-miss turns
        for ($i = 0; $i < 15; $i++) {
            $result = (new BattleService())->run();
            foreach ($result['turns'] as $turn) {
                if ($turn['lucky_miss']) {
                    $this->assertSame(0, $turn['damage_dealt']);
                }
            }
        }
    }

    public function test_turn_numbers_are_sequential_starting_at_one(): void
    {
        $result = (new BattleService())->run();

        foreach ($result['turns'] as $index => $turn) {
            $this->assertSame($index + 1, $turn['turn']);
        }
    }

    public function test_attacker_alternates_each_turn(): void
    {
        $result = (new BattleService())->run();
        $turns  = $result['turns'];

        for ($i = 1; $i < count($turns); $i++) {
            $this->assertNotSame(
                $turns[$i - 1]['attacker'],
                $turns[$i]['attacker'],
                "Attacker should alternate every turn (failed at turn {$turns[$i]['turn']})"
            );
        }
    }

    public function test_kratos_starting_stats_are_within_expected_ranges(): void
    {
        $result = (new BattleService())->run();
        $k      = $result['kratos'];

        $this->assertGreaterThanOrEqual(65, $k['health']);
        $this->assertLessThanOrEqual(100, $k['health']);
        $this->assertGreaterThanOrEqual(75, $k['strength']);
        $this->assertLessThanOrEqual(90, $k['strength']);
    }

    public function test_monster_starting_stats_are_within_expected_ranges(): void
    {
        $result = (new BattleService())->run();
        $m      = $result['monster'];

        $this->assertGreaterThanOrEqual(50, $m['health']);
        $this->assertLessThanOrEqual(80, $m['health']);
        $this->assertGreaterThanOrEqual(55, $m['strength']);
        $this->assertLessThanOrEqual(80, $m['strength']);
    }

    public function test_battle_id_is_a_positive_integer(): void
    {
        $result = (new BattleService())->run();
        $this->assertIsInt($result['battle_id']);
        $this->assertGreaterThan(0, $result['battle_id']);
    }

    public function test_battle_log_is_written_to_database(): void
    {
        (new BattleService())->run();
        $this->assertDatabaseCount('battle_logs', 1);
    }

    public function test_turn_logs_are_written_to_database(): void
    {
        $result = (new BattleService())->run();
        $this->assertDatabaseCount('turn_logs', $result['total_turns']);
    }

    public function test_winner_is_persisted_to_battle_log(): void
    {
        $result = (new BattleService())->run();
        $this->assertDatabaseHas('battle_logs', [
            'id'     => $result['battle_id'],
            'winner' => $result['winner'],
        ]);
    }
}
