<?php

namespace App;

class FileSyncService {
    private $githubClient;
    private $gitlabClient;
    private $workdir;

    public function __construct($githubClient, $gitlabClient, $workdir) {
        $this->githubClient = $githubClient;
        $this->gitlabClient = $gitlabClient;
        $this->workdir = $workdir;
    }

    public function sync() {
        $mergedPRs = $this->githubClient->getMergedPullRequests();
        foreach ($mergedPRs as $pr) {
            $this->cloneAndPush($pr['head']['repo']['clone_url'], $pr['head']['ref']);
        }
    }

    private function cloneAndPush($cloneUrl, $branch) {
        $repoPath = "{$this->workdir}/repo";
        exec("rm -rf $repoPath && git clone -b $branch $cloneUrl $repoPath");
        exec("cd $repoPath && git remote add gitlab https://gitlab.com/{$this->gitlabClient->repo}.git");
        exec("cd $repoPath && git push gitlab $branch");
    }
}
