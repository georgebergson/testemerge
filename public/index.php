<?php

require_once __DIR__ . '/../vendor/autoload.php';


use App\GitHubClient;
use App\GitLabClient;
use App\FileSyncService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$githubClient = new GitHubClient($_ENV['GITHUB_TOKEN'], $_ENV['GITHUB_REPO']);
$gitlabClient = new GitLabClient($_ENV['GITLAB_TOKEN'], $_ENV['GITLAB_REPO']);
$service = new FileSyncService($githubClient, $gitlabClient, $_ENV['WORKDIR']);

$service->sync();
