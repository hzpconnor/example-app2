<?php

namespace App\Http\Controllers;

use App\Services\BetfairAgentService;
use Illuminate\Http\Client\RequestException;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function __construct(private BetfairAgentService $agentService) {}

    public function index(): View
    {
        try {
            $matches = $this->agentService->getMatches();
        } catch (RequestException $e) {
            $matches = [];
            session()->flash('error', '获取比赛列表失败，请稍后重试。');
        }

        return view('matches.index', compact('matches'));
    }

    public function analyze(string $matchId): View
    {
        try {
            $analysis = $this->agentService->analyzeMatch($matchId);
        } catch (RequestException $e) {
            $analysis = null;
            session()->flash('error', '获取分析数据失败，请稍后重试。');
        }

        return view('matches.analyze', compact('matchId', 'analysis'));
    }
}
