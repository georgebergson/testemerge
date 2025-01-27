<?php

namespace App;

use GuzzleHttp\Client;

class GitHubClient {
    private $client;
    private $repo;

    public function __construct($token, $repo) {
        $this->repo = $repo;
        $this->client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => "token $token",
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
    }

    public function getMergedPullRequests() {
        $response = $this->client->get("repos/{$this->repo}/pulls", [
            'query' => ['state' => 'closed'],
        ]);
        $pulls = json_decode($response->getBody(), true);
        return array_filter($pulls, fn($pr) => $pr['merged_at'] !== null);
    }
}
