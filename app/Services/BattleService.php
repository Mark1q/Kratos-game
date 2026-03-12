<?php

namespace App\Services;

use App\Models\Fighter;
use App\Models\Kratos;
use App\Models\Monster;
use App\Models\BattleLog;
use App\Models\TurnLog;

class BattleService
{
    private const MAX_TURNS = 15;

    private Kratos $kratos;
    private Monster $monster;
    private array $turns = [];

    public function __construct()
    {
        $this->kratos  = new Kratos();
        $this->monster = new Monster();
    }

    public function run(): array
    {
        $kratosStart  = $this->kratos->toArray();
        $monsterStart = $this->monster->toArray();

        [$attacker, $defender] = $this->determineOrder();

        $battle = BattleLog::create([
            'kratos_health'    => $kratosStart['health'],
            'kratos_strength'  => $kratosStart['strength'],
            'kratos_defence'   => $kratosStart['defence'],
            'kratos_speed'     => $kratosStart['speed'],
            'kratos_luck'      => $kratosStart['luck'],
            'monster_health'   => $monsterStart['health'],
            'monster_strength' => $monsterStart['strength'],
            'monster_defence'  => $monsterStart['defence'],
            'monster_speed'    => $monsterStart['speed'],
            'monster_luck'     => $monsterStart['luck'],
            'winner'           => null,
            'total_turns'      => 0,
        ]);

        $turnNumber = 0;

        while ($turnNumber < self::MAX_TURNS && $this->kratos->isAlive() && $this->monster->isAlive()) {
            $turnNumber++;
            $turnResult  = $this->processTurn($attacker, $defender, $turnNumber, $battle->id);
            $this->turns[] = $turnResult;

            // Swap roles
            [$attacker, $defender] = [$defender, $attacker];
        }

        $winner = $this->determineWinner();

        $battle->update([
            'winner'      => $winner,
            'total_turns' => $turnNumber,
        ]);

        return [
            'battle_id'   => $battle->id,
            'kratos'      => $kratosStart,
            'monster'     => $monsterStart,
            'turns'       => $this->turns,
            'winner'      => $winner,
            'total_turns' => $turnNumber,
        ];
    }

    private function processTurn(Fighter $attacker, Fighter $defender, int $turnNumber, int $battleId): array
    {
        $skillUsed   = null;
        $luckyMiss   = false;
        $totalDamage = 0;

        if ($defender->isLucky()) {
            $luckyMiss = true;
        } else {
            $baseDamage = DamageCalculator::calculate($attacker, $defender);
            $hits       = [$baseDamage];

            if ($attacker instanceof Kratos) {
                $skill = $attacker->getAttackSkill();
                if ($skill) {
                    $skillUsed = $skill->getName();
                    $hits      = $skill->modifyAttack($baseDamage);
                }
            }

            if ($defender instanceof Kratos) {
                $defSkill = $defender->getDefenceSkill();
                if ($defSkill) {
                    $skillUsed = $defSkill->getName();
                    $hits      = array_map(fn($h) => $defSkill->modifyDefence($h), $hits);
                }
            }

            foreach ($hits as $hit) {
                $totalDamage += $hit;
                $defender->takeDamage($hit);
            }
        }

        TurnLog::create([
            'battle_id'                 => $battleId,
            'turn_number'               => $turnNumber,
            'attacker'                  => $attacker->name,
            'skill_used'                => $skillUsed,
            'damage_dealt'              => $totalDamage,
            'was_lucky_miss'            => $luckyMiss,
            'defender_health_remaining' => $defender->health,
        ]);

        return [
            'turn'                      => $turnNumber,
            'attacker'                  => $attacker->name,
            'defender'                  => $defender->name,
            'skill_used'                => $skillUsed,
            'lucky_miss'                => $luckyMiss,
            'damage_dealt'              => $totalDamage,
            'defender_health_remaining' => $defender->health,
        ];
    }

    private function determineOrder(): array
    {
        if ($this->kratos->speed > $this->monster->speed) {
            return [$this->kratos, $this->monster];
        }

        if ($this->monster->speed > $this->kratos->speed) {
            return [$this->monster, $this->kratos];
        }

        // same speed. check higher luck
        return $this->kratos->luck >= $this->monster->luck
            ? [$this->kratos, $this->monster]
            : [$this->monster, $this->kratos];
    }

    private function determineWinner(): string
    {
        if (!$this->kratos->isAlive())  return 'Monster';
        if (!$this->monster->isAlive()) return 'Kratos';
        return 'Draw';
    }
}
