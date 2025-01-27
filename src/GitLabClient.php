<?php

namespace App;

use GuzzleHttp\Client;

class GitLabClient {
    private $client;
    private $repo;

    public function __construct($token, $repo) {
        $this->repo = $repo;
        $this->client = new Client([
            'base_uri' => 'https://gitlab.com/api/v4/',
            'headers' => [
                'Authorization' => "Bearer $token",
            ],
        ]);
    }

    public function pushChanges($branch, $commitMessage, $filePath) {
        // Implementação do push via API GitLab.
        // Exemplo: Fazer upload de arquivos usando o endpoint de commits.
    }
}
