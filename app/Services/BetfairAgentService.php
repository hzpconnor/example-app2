<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class BetfairAgentService
{
    private const BASE_URL = 'https://myagent.mjktech.cn';

    private function client(): PendingRequest
    {
        $client = Http::connectTimeout(5);

        if (app()->isLocal()) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    public function getMatches(): array
    {
        $response = $this->client()
            ->timeout(15)
            ->retry(2, 500)
            ->get(self::BASE_URL.'/matches');

        $response->throw();

        return $response->json('matches', []);
    }

    public function analyzeMatch(string $matchId): ?array
    {
        $response = $this->client()
            ->timeout(30)
            ->retry(2, 500)
            ->get(self::BASE_URL."/matches/{$matchId}/analyze");

        if ($response->notFound()) {
            return null;
        }

        $response->throw();

        return $response->json();
    }
}
