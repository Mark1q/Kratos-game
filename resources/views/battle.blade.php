<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kratos Battle Simulator</title>
    <style>
        body { font-family: monospace; background: #1a1a2e; color: #eee; padding: 2rem; }
        h1 { color: #e94560; }
        h2 { color: #e94560; margin-top: 2rem; }
        a.btn { display: inline-block; background: #e94560; color: white; text-decoration: none; padding: 10px 24px; font-size: 1rem; border-radius: 4px; margin-top: 1rem; }
        a.btn:hover { background: #c73652; }
        .stats { display: flex; gap: 2rem; margin: 1.5rem 0; }
        .stat-box { background: #16213e; padding: 12px 18px; border-radius: 4px; min-width: 160px; }
        .stat-box h3 { margin: 0 0 8px; color: #e94560; }
        .stat-box p { margin: 3px 0; font-size: 0.85rem; }
        .turn { background: #16213e; margin: 6px 0; padding: 10px 14px; border-left: 3px solid #e94560; border-radius: 2px; }
        .attack-skill { color: #f5a623; font-weight: bold; }
        .defence-skill { color: #4ecca3; font-weight: bold; }
        .miss { color: #aaa; font-style: italic; }
        .winner { font-size: 1.4rem; color: #4ecca3; font-weight: bold; margin: 1.5rem 0; }
        .draw { color: #aaa; }
    </style>
</head>
<body>

<h1>⚔️ Kratos Battle Simulator</h1>
<a href="{{ route('battle.start') }}" class="btn">▶ Start New Battle</a>
<a href="{{ route('battle.history') }}" class="btn" style="background:#16213e; margin-left:10px;">📜 View History</a>

@isset($result)
    <h2>Starting Stats</h2>
    <div class="stats">
        <div class="stat-box">
            <h3>Kratos</h3>
            <p>❤️ Health: {{ $result['kratos']['health'] }}</p>
            <p>⚔️ Strength: {{ $result['kratos']['strength'] }}</p>
            <p>🛡️ Defence: {{ $result['kratos']['defence'] }}</p>
            <p>💨 Speed: {{ $result['kratos']['speed'] }}</p>
            <p>🍀 Luck: {{ $result['kratos']['luck'] * 100 }}%</p>
        </div>
        <div class="stat-box">
            <h3>Monster</h3>
            <p>❤️ Health: {{ $result['monster']['health'] }}</p>
            <p>⚔️ Strength: {{ $result['monster']['strength'] }}</p>
            <p>🛡️ Defence: {{ $result['monster']['defence'] }}</p>
            <p>💨 Speed: {{ $result['monster']['speed'] }}</p>
            <p>🍀 Luck: {{ $result['monster']['luck'] * 100 }}%</p>
        </div>
    </div>

    <h2>Battle Log</h2>
    @foreach ($result['turns'] as $turn)
        <div class="turn">
            <strong>Turn {{ $turn['turn'] }}:</strong>
            {{ $turn['attacker'] }} attacks {{ $turn['defender'] }}.

            @if ($turn['lucky_miss'])
                <span class="miss">Miss! {{ $turn['defender'] }} got lucky and avoided the attack.</span>
            @else
                @if ($turn['attack_skill'] ?? null)
                    <span class="attack-skill">[{{ $turn['attack_skill'] }}]</span>
                @endif
                Dealt <strong>{{ $turn['damage_dealt'] }}</strong> damage.
                @if ($turn['defence_skill'] ?? null)
                    <span class="defence-skill">[{{ $turn['defence_skill'] }}] — damage was halved!</span>
                    @endif
                    @endif

                    &mdash; {{ $turn['defender'] }} health remaining: <strong>{{ $turn['defender_health_remaining'] }}</strong>
        </div>
    @endforeach

    <div class="winner">
        @if ($result['winner'] === 'Draw')
            <span class="draw">⏱️ Draw — max turns ({{ $result['total_turns'] }}) reached!</span>
        @else
            🏆 {{ $result['winner'] }} wins after {{ $result['total_turns'] }} turns!
        @endif
    </div>
@endisset

</body>
</html>
