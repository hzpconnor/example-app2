<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('matches.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                AI 盘口分析
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if (is_null($analysis))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        未找到比赛 <span class="font-mono text-gray-700">{{ $matchId }}</span> 的分析数据
                    </div>
                </div>
            @else
                {{-- 比赛基本信息 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">比赛信息</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                        @if (!empty($analysis['homeTeam']))
                            <div>
                                <p class="text-xs text-gray-500 mb-1">主队</p>
                                <p class="font-medium text-gray-900">{{ $analysis['homeTeam'] }}</p>
                            </div>
                        @endif
                        @if (!empty($analysis['awayTeam']))
                            <div>
                                <p class="text-xs text-gray-500 mb-1">客队</p>
                                <p class="font-medium text-gray-900">{{ $analysis['awayTeam'] }}</p>
                            </div>
                        @endif
                        @if (!empty($analysis['league']))
                            <div>
                                <p class="text-xs text-gray-500 mb-1">联赛</p>
                                <p class="font-medium text-gray-900">{{ $analysis['league'] }}</p>
                            </div>
                        @endif
                        @if (!empty($analysis['commenceTime']))
                            <div>
                                <p class="text-xs text-gray-500 mb-1">开赛时间</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($analysis['commenceTime'])->setTimezone('Asia/Shanghai')->format('m-d H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 预测推荐 --}}
                @if (!empty($analysis['recommendation']) || !empty($analysis['confidence']))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">预测推荐</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if (!empty($analysis['handicapRecommendation']))
                                <div class="flex items-start gap-4 p-4 bg-indigo-50 rounded-lg">
                                    <div class="shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-indigo-600 mb-1">亚盘推荐</p>
                                        <p class="text-gray-800">{{ $analysis['handicapRecommendation'] }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($analysis['ouRecommendation']))
                                <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-lg">
                                    <div class="shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-emerald-600 mb-1">大小球推荐</p>
                                        <p class="text-gray-800">{{ $analysis['ouRecommendation'] }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($analysis['confidence']))
                                <div class="flex items-center gap-3 pt-2">
                                    <span class="text-sm text-gray-500">置信度</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all"
                                             style="width: {{ is_numeric($analysis['confidence']) ? $analysis['confidence'] : rtrim($analysis['confidence'], '%') }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-indigo-700">{{ $analysis['confidence'] }}{{ is_numeric($analysis['confidence']) ? '%' : '' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- 分析依据 --}}
                @if (!empty($analysis['analysis']) || !empty($analysis['reasoning']))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">分析依据</h3>
                        </div>
                        <div class="p-6 text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $analysis['analysis'] ?? $analysis['reasoning'] }}
                        </div>
                    </div>
                @endif

                {{-- 原始数据（兜底展示所有字段）--}}
                @php
                    $knownKeys = ['homeTeam', 'awayTeam', 'league', 'commenceTime', 'handicapRecommendation', 'ouRecommendation', 'confidence', 'analysis', 'reasoning'];
                    $extraFields = collect($analysis)->except($knownKeys)->except('matchId');
                @endphp
                @if ($extraFields->isNotEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">详细数据</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach ($extraFields as $key => $value)
                                <div class="flex gap-3">
                                    <span class="text-sm text-gray-500 w-40 shrink-0">{{ $key }}</span>
                                    <span class="text-sm text-gray-800 break-all">
                                        @if (is_array($value))
                                            {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif

            <div class="text-center">
                <a href="{{ route('matches.index') }}"
                   class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                    ← 返回比赛列表
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
