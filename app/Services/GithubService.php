<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService {
    public function getReleases() {
        $releases = Http::withToken(config('services.github.token'))
        ->acceptJson()
        ->get(
            "https://api.github.com/repos/" .
            config('services.github.owner') . "/" .
            config('services.github.repo') . "/releases",
            ['per_page' => 5]
        )
        ->throw()
        ->json();

        return $releases;
    }

    public function getCommits($base, $head) {
        $commits = Http::withToken(config('services.github.token'))
            ->acceptJson()
            ->get(
                "https://api.github.com/repos/" .
                config('services.github.owner') . "/" .
                config('services.github.repo') .
                "/compare/$base...$head"
            )
            ->throw()
            ->json();

        return $commits;
    }

    public function getChangeLog() {
        $releases = collect($this->getReleases())
            ->sortByDesc(fn ($r) => $r['published_at'])
            ->values();

        $changelog = collect($releases)->map(function ($release, $index) use ($releases) {
            $head = $release['tag_name'];

            // Previous release tag
            $base = $releases[$index + 1]['tag_name'] ?? null;

            if (!$base) {
                return null; // Skip first release (no previous comparison)
            }

            $compare = $this->getCommits($base, $head);

            return [
                'version' => $release['name'] ?? $release['tag_name'],
                'tag' => $release['tag_name'],
                'date' => $release['published_at'],
                'url' => $release['html_url'],
                'commits' => collect($compare['commits'])->map(fn ($commit) => [
                    'sha' => $commit['sha'],
                    'message' => $commit['commit']['message'],
                    'author' => $commit['commit']['author']['name'] ?? 'Unknown',
                    'avatar' => $commit['author']['avatar_url'] ?? null,
                    'date' => $commit['commit']['author']['date'],
                    'url' => $commit['html_url'],
                ])->sortByDesc(fn ($r) => $r['date'])->values()->toArray(),
            ];
        })->filter()->values();

        return $changelog;
    }
}
