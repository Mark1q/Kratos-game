<?php

namespace App\Http\Controllers;

use App\Models\BattleLog;
use App\Services\BattleService;

class BattleController extends Controller
{
    public function index()
    {
        return view('battle');
    }

    public function start()
    {
        $battle = new BattleService();
        $result = $battle->run();

        return view('battle', ['result' => $result]);
    }

    public function history()
    {
        $battles = BattleLog::with('turns')->orderBy('created_at', 'desc')->get();
        return view('history', ['battles' => $battles]);
    }
}
