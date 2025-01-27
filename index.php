<?php
// Configurações
$gitlabRepo = 'https://gitlab.com/george.bergson/testegithub.git'; // URL do repositório GitLab
$gitlabToken = 'glpat-yL96f699xiT_HjU8WDLy'; // Token do GitLab
$githubSecret = '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'; // O mesmo segredo configurado no GitHub

// Validar o payload do GitHub
$payload = file_get_contents('php://input');
$signature = 'sha256=' . hash_hmac('sha256', $payload, $githubSecret);

if (!hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
    http_response_code(403);
    echo 'Invalid signature';
    exit;
}

// Decodificar o payload
$data = json_decode($payload, true);

// Verificar se é um evento de push
if ($data['ref'] === 'refs/heads/main') {
    // Clonar ou atualizar o repositório localmente
    $repoDir = '/tmp/github-repo'; // Diretório temporário para clonar o repositório
    if (!is_dir($repoDir)) {
        exec("git clone --mirror {$data['repository']['clone_url']} $repoDir");
    } else {
        exec("cd $repoDir && git fetch --all");
    }

    // Configurar o remoto do GitLab e fazer o push
    exec("cd $repoDir && git remote add gitlab $gitlabRepo 2>/dev/null || true");
    exec("cd $repoDir && git push gitlab --all --force");
    exec("cd $repoDir && git push gitlab --tags --force");

    echo 'Sync complete';
} else {
    echo 'Not a push event to main branch';
}
