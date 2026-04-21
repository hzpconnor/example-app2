<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('matches.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">AI 盘口分析</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if (is_null($analysis))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-10 text-center text-gray-500">
                        未找到比赛 <span class="font-mono text-gray-700">{{ $matchId }}</span> 的分析数据
                    </div>
                </div>
            @else
                @php
                    $match   = $analysis['match']   ?? $analysis;
                    $summary = $analysis['summary'] ?? null;
                    $odds    = $analysis['odds']    ?? [];
                    $history = $analysis['history'] ?? [];

                    $homeTeam     = $match['homeTeam']     ?? '';
                    $awayTeam     = $match['awayTeam']     ?? '';
                    $league       = $match['league']       ?? '';
                    $commenceTime = $match['commenceTime'] ?? ($analysis['commenceTime'] ?? '');
                    $ahLabel      = $match['ahLabel']      ?? '';
                    $totalLine    = $match['totalLine']    ?? '';

                    $handicapPick = $summary['handicapPick'] ?? ($analysis['handicapRecommendation'] ?? null);
                    $totalsPick   = $summary['totalsPick']   ?? ($analysis['ouRecommendation'] ?? null);
                    $cornersPick  = $summary['cornersPick']  ?? null;
                    $confidence   = $summary['confidence']   ?? ($analysis['confidence'] ?? null);
                    $confidenceNum = $confidence ? (int) filter_var($confidence, FILTER_SANITIZE_NUMBER_INT) : 0;

                    $prediction = $analysis['prediction'] ?? ($analysis['analysis'] ?? ($analysis['reasoning'] ?? null));

                    $homeForm = $history['homeForm'] ?? [];
                    $awayForm = $history['awayForm'] ?? [];
                    $h2hList  = $history['h2h']      ?? [];

                    $handicapOdds = $odds['handicap']  ?? [];
                    $h2hOdds      = $odds['h2h']       ?? [];
                    $totalsOdds   = $odds['totals']    ?? [];
                    $htHandicap   = $odds['htHandicap'] ?? [];
                    $htTotals     = $odds['htTotals']  ?? [];
                @endphp

                {{-- 比赛标题卡 --}}
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-xl shadow-md text-white overflow-hidden">
                    <div class="px-6 py-2 bg-white/10 text-center text-xs font-medium tracking-wide">
                        {{ $league }}
                        @if ($commenceTime)
                            &nbsp;·&nbsp;
                            {{ \Carbon\Carbon::parse($commenceTime)->setTimezone('Asia/Shanghai')->format('m月d日 H:i') }} (北京时间)
                        @endif
                    </div>
                    <div class="flex items-center justify-center gap-4 px-6 py-6">
                        <div class="flex-1 text-right">
                            <p class="text-lg font-bold leading-snug">{{ $homeTeam ?: '—' }}</p>
                            <p class="text-xs text-indigo-200 mt-1">主队</p>
                        </div>
                        <div class="text-center shrink-0 px-4">
                            <span class="text-2xl font-black text-white/60">VS</span>
                            @if ($ahLabel)
                                <p class="text-xs text-indigo-200 mt-1">让球：{{ $ahLabel }}</p>
                            @endif
                            @if ($totalLine)
                                <p class="text-xs text-indigo-200">大小：{{ $totalLine }}</p>
                            @endif
                        </div>
                        <div class="flex-1 text-left">
                            <p class="text-lg font-bold leading-snug">{{ $awayTeam ?: '—' }}</p>
                            <p class="text-xs text-indigo-200 mt-1">客队</p>
                        </div>
                    </div>
                </div>

                {{-- 预测摘要 --}}
                @if ($handicapPick || $totalsPick || $cornersPick || $confidence)
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        @if ($handicapPick)
                            <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-indigo-500">
                                <p class="text-xs text-gray-400 mb-2 font-medium">亚盘推荐</p>
                                <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $handicapPick }}</p>
                            </div>
                        @endif
                        @if ($totalsPick)
                            <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-emerald-500">
                                <p class="text-xs text-gray-400 mb-2 font-medium">大小球推荐</p>
                                <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $totalsPick }}</p>
                            </div>
                        @endif
                        @if ($cornersPick && $cornersPick !== '无盘口')
                            <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-amber-500">
                                <p class="text-xs text-gray-400 mb-2 font-medium">角球推荐</p>
                                <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $cornersPick }}</p>
                            </div>
                        @endif
                        @if ($confidence)
                            <div class="bg-white rounded-xl shadow-sm p-4 border-t-4 border-violet-500">
                                <p class="text-xs text-gray-400 mb-2 font-medium">预测置信度</p>
                                <p class="text-2xl font-black text-violet-600">{{ $confidence }}{{ !str_contains((string)$confidence, '%') ? '%' : '' }}</p>
                                <div class="mt-2 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-violet-500 h-1.5 rounded-full" @style(['width: '.min($confidenceNum, 100).'%'])></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- AI 分析内容 --}}
                @if ($prediction)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="font-semibold text-gray-800 text-sm">AI 分析详情</h3>
                        </div>
                        <div class="p-6 text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $prediction }}</div>
                    </div>
                @endif

                {{-- 近期战绩 --}}
                @if (!empty($homeForm) || !empty($awayForm))
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ([['label' => $homeTeam ?: '主队', 'form' => $homeForm, 'color' => 'indigo'], ['label' => $awayTeam ?: '客队', 'form' => $awayForm, 'color' => 'emerald']] as $side)
                            @if (!empty($side['form']))
                                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-100 text-sm font-semibold text-gray-700">
                                        {{ $side['label'] }} 近期战绩
                                    </div>
                                    <div class="divide-y divide-gray-50">
                                        @foreach ($side['form'] as $game)
                                            <div class="flex items-center gap-3 px-4 py-2.5 text-xs">
                                                <span class="text-gray-400 w-14 shrink-0">{{ $game['date'] ?? '' }}</span>
                                                <span class="text-gray-500 w-6 shrink-0 text-center">{{ $game['venue'] ?? '' }}</span>
                                                <span class="text-gray-700 flex-1 truncate">{{ $game['opponent'] ?? '' }}</span>
                                                <span class="font-mono text-gray-800 w-9 text-center">{{ $game['score'] ?? '' }}</span>
                                                @php $r = $game['result'] ?? ''; @endphp
                                                <span class="w-7 text-center font-bold rounded text-xs py-0.5
                                                    {{ $r === '胜' ? 'text-emerald-700 bg-emerald-50' : ($r === '负' ? 'text-red-600 bg-red-50' : 'text-gray-500 bg-gray-100') }}">
                                                    {{ $r }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- 赔率对比 --}}
                @if (!empty($handicapOdds) || !empty($totalsOdds) || !empty($h2hOdds))
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800 text-sm">赔率数据</h3>
                        </div>
                        <div class="overflow-x-auto">

                            @if (!empty($handicapOdds))
                                <div class="px-6 pt-4 pb-1">
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">亚盘（让球）</p>
                                </div>
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-500">
                                            <th class="px-6 py-2 text-left font-medium">庄家</th>
                                            <th class="px-4 py-2 text-center font-medium">让球</th>
                                            <th class="px-4 py-2 text-center font-medium">主队赔率</th>
                                            <th class="px-4 py-2 text-center font-medium">客队赔率</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach ($handicapOdds as $row)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="px-6 py-2 text-gray-700">{{ $row['bookmaker'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center text-gray-600 font-mono">{{ $row['line'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['homeOdds'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['awayOdds'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (!empty($totalsOdds))
                                <div class="px-6 pt-5 pb-1">
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">大小球</p>
                                </div>
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-500">
                                            <th class="px-6 py-2 text-left font-medium">庄家</th>
                                            <th class="px-4 py-2 text-center font-medium">球数线</th>
                                            <th class="px-4 py-2 text-center font-medium">大球赔率</th>
                                            <th class="px-4 py-2 text-center font-medium">小球赔率</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach ($totalsOdds as $row)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="px-6 py-2 text-gray-700">{{ $row['bookmaker'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center text-gray-600 font-mono">{{ $row['line'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-emerald-700">{{ $row['overOdds'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-red-600">{{ $row['underOdds'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (!empty($h2hOdds))
                                <div class="px-6 pt-5 pb-1">
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">欧洲赔率</p>
                                </div>
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-500">
                                            <th class="px-6 py-2 text-left font-medium">庄家</th>
                                            <th class="px-4 py-2 text-center font-medium">主胜</th>
                                            <th class="px-4 py-2 text-center font-medium">平局</th>
                                            <th class="px-4 py-2 text-center font-medium">客胜</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach ($h2hOdds as $row)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="px-6 py-2 text-gray-700">{{ $row['bookmaker'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['homeOdds'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-gray-600">{{ $row['drawOdds'] ?? '' }}</td>
                                                <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['awayOdds'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (!empty($htHandicap) || !empty($htTotals))
                                <div class="px-6 pt-5 pb-1">
                                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">半场盘口</p>
                                </div>
                                @if (!empty($htHandicap))
                                    <table class="w-full text-xs mb-2">
                                        <thead>
                                            <tr class="bg-gray-50 text-gray-500">
                                                <th class="px-6 py-2 text-left font-medium">庄家（让球）</th>
                                                <th class="px-4 py-2 text-center font-medium">让球</th>
                                                <th class="px-4 py-2 text-center font-medium">主</th>
                                                <th class="px-4 py-2 text-center font-medium">客</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach ($htHandicap as $row)
                                                <tr class="hover:bg-gray-50/50">
                                                    <td class="px-6 py-2 text-gray-700">{{ $row['bookmaker'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center text-gray-600 font-mono">{{ $row['line'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['homeOdds'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center font-mono text-gray-800">{{ $row['awayOdds'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if (!empty($htTotals))
                                    <table class="w-full text-xs pb-4">
                                        <thead>
                                            <tr class="bg-gray-50 text-gray-500">
                                                <th class="px-6 py-2 text-left font-medium">庄家（大小）</th>
                                                <th class="px-4 py-2 text-center font-medium">球数线</th>
                                                <th class="px-4 py-2 text-center font-medium">大</th>
                                                <th class="px-4 py-2 text-center font-medium">小</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach ($htTotals as $row)
                                                <tr class="hover:bg-gray-50/50">
                                                    <td class="px-6 py-2 text-gray-700">{{ $row['bookmaker'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center text-gray-600 font-mono">{{ $row['line'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center font-mono text-emerald-700">{{ $row['overOdds'] ?? '' }}</td>
                                                    <td class="px-4 py-2 text-center font-mono text-red-600">{{ $row['underOdds'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endif

                        </div>
                    </div>
                @endif

                {{-- 历史交锋 --}}
                @if (!empty($h2hList))
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 text-sm font-semibold text-gray-700">历史交锋</div>
                        <div class="divide-y divide-gray-50">
                            @foreach ($h2hList as $game)
                                <div class="flex items-center gap-3 px-6 py-2.5 text-xs">
                                    <span class="text-gray-400 w-16 shrink-0">{{ $game['date'] ?? '' }}</span>
                                    <span class="text-gray-700 flex-1">{{ $game['homeTeam'] ?? ($game['opponent'] ?? '') }}</span>
                                    <span class="font-mono text-gray-800 w-10 text-center">{{ $game['score'] ?? '' }}</span>
                                    <span class="text-gray-700 flex-1 text-right">{{ $game['awayTeam'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif

            <div class="text-center pb-4">
                <a href="{{ route('matches.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                    ← 返回比赛列表
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
