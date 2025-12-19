<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class GithubService {
    public function getCommits(?string $search = null) {
        $response = Http::withToken(config('services.github.token'))
            ->acceptJson()
            ->get(
                "https://api.github.com/repos/" .
                config('services.github.owner') . "/" .
                config('services.github.repo') . "/commits",
                [
                    'per_page' => 10,
                ]
            );

        $response->throw();

        return collect($response->json())->map(fn ($commit) => [
            'sha' => $commit['sha'],
            'message' => $commit['commit']['message'],
            'author' => $commit['commit']['author']['name'] ?? 'Unknown',
            'avatar' => $commit['author']['avatar_url'] ?? null,
            'date' => $commit['commit']['author']['date'],
            'url' => $commit['html_url'],
        ])->toArray();
    }
}
