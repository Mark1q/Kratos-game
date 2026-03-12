<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battle History</title>
    <style>
        body { font-family: monospace; background: #1a1a2e; color: #eee; padding: 2rem; }
        h1 { color: #e94560; }
        a.btn { display: inline-block; background: #e94560; color: white; text-decoration: none; padding: 8px 20px; font-size: 0.9rem; border-radius: 4px; margin-bottom: 2rem; }
        a.btn:hover { background: #c73652; }
        .battle { background: #16213e; margin: 12px 0; border-radius: 6px; overflow: hidden; }
        .battle-header { padding: 12px 16px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #e94560; }
        .battle-header:hover { background: #1e2a4a; }
        .battle-header.kratos-win { border-left-color: #4ecca3; }
        .battle-header.monster-win { border-left-color: #e94560; }
        .battle-header.draw { border-left-color: #aaa; }
        .winner-badge { font-weight: bold; font-size: 0.85rem; padding: 3px 10px; border-radius: 20px; }
        .kratos-win .winner-badge { background: #4ecca3; color: #1a1a2e; }
        .monster-win .winner-badge { background: #e94560; color: white; }
        .draw .winner-badge { background: #aaa; color: #1a1a2e; }
        .battle-body { display: none; padding: 16px; border-top: 1px solid #2a2a4a; }
        .battle-body.open { display: block; }
        .stats { display: flex; gap: 2rem; margin-bottom: 1rem; }
        .stat-box { background: #0f1729; padding: 10px 14px; border-radius: 4px; min-width: 150px; }
        .stat-box h3 { margin: 0 0 6px; color: #e94560; font-size: 0.9rem; }
        .stat-box p { margin: 2px 0; font-size: 0.8rem; }
        .turn { background: #0f1729; margin: 4px 0; padding: 8px 12px; border-left: 2px solid #e94560; font-size: 0.82rem; }
        .attack-skill { color: #f5a623; font-weight: bold; }
        .defence-skill { color: #4ecca3; font-weight: bold; }
        .miss { color: #aaa; font-style: italic; }
        .meta { font-size: 0.78rem; color: #888; }
        .no-battles { color: #aaa; margin-top: 2rem; }
        .toggle-arrow { font-size: 0.8rem; color: #aaa; }
    </style>
</head>
<body>

<h1>📜 Battle History</h1>
<a href="{{ route('battle.start') }}" class="btn">▶ Start New Battle</a>

@if ($battles->isEmpty())
    <p class="no-battles">No battles yet. Start one!</p>
@else
    <p class="meta">{{ $battles->count() }} battle(s) recorded.</p>

    @foreach ($battles as $battle)
        @php
            $cssClass = match($battle->winner) {
                'Kratos'  => 'kratos-win',
                'Monster' => 'monster-win',
                default   => 'draw',
            };
            $icon = match($battle->winner) {
                'Kratos'  => '🏆 Kratos wins',
                'Monster' => '💀 Monster wins',
                default   => '⏱️ Draw',
            };
        @endphp

        <div class="battle">
            <div class="battle-header {{ $cssClass }}" onclick="toggle({{ $battle->id }})">
                <div>
                    <strong>Battle #{{ $battle->id }}</strong>
                    <span class="meta"> — {{ $battle->created_at->diffForHumans() }} — {{ $battle->total_turns }} turns</span>
                </div>
                <div style="display:flex; align-items:center; gap: 10px;">
                    <span class="winner-badge">{{ $icon }}</span>
                    <span class="toggle-arrow" id="arrow-{{ $battle->id }}">▼</span>
                </div>
            </div>

            <div class="battle-body" id="body-{{ $battle->id }}">
                <div class="stats">
                    <div class="stat-box">
                        <h3>Kratos</h3>
                        <p>❤️ Health: {{ $battle->kratos_health }}</p>
                        <p>⚔️ Strength: {{ $battle->kratos_strength }}</p>
                        <p>🛡️ Defence: {{ $battle->kratos_defence }}</p>
                        <p>💨 Speed: {{ $battle->kratos_speed }}</p>
                        <p>🍀 Luck: {{ $battle->kratos_luck * 100 }}%</p>
                    </div>
                    <div class="stat-box">
                        <h3>Monster</h3>
                        <p>❤️ Health: {{ $battle->monster_health }}</p>
                        <p>⚔️ Strength: {{ $battle->monster_strength }}</p>
                        <p>🛡️ Defence: {{ $battle->monster_defence }}</p>
                        <p>💨 Speed: {{ $battle->monster_speed }}</p>
                        <p>🍀 Luck: {{ $battle->monster_luck * 100 }}%</p>
                    </div>
                </div>

                @foreach ($battle->turns as $turn)
                    <div class="turn">
                        <strong>Turn {{ $turn->turn_number }}:</strong>
                        {{ $turn->attacker }} attacks.

                        @if ($turn->was_lucky_miss)
                            <span class="miss">Miss! Defender got lucky.</span>
                        @else
                            @if ($turn->skill_used)
                                <span class="{{ str_contains($turn->skill_used, 'Armour') ? 'defence-skill' : 'attack-skill' }}">[{{ $turn->skill_used }}]</span>
                            @endif
                            Dealt <strong>{{ $turn->damage_dealt }}</strong> damage.
                        @endif

                        — Health remaining: <strong>{{ $turn->defender_health_remaining }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif

<script>
    function toggle(id) {
        const body  = document.getElementById('body-' + id);
        const arrow = document.getElementById('arrow-' + id);
        const open  = body.classList.toggle('open');
        arrow.textContent = open ? '▲' : '▼';
    }
</script>

</body>
</html>
