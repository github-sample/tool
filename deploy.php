<?php
require 'recipe/composer.php';
// デプロイ先
server('production', '【IP】', 【ポート番号】)
  ->user('【ユーザー名】')
  ->identityFile()
  ->forwardAgent()
  ->env('deploy_path', '/var/www/html/tool-deploy');

set('ssh_type', 'native');

// リポジトリ
set('repository', 'https://github.com/github-sample/tool.git');
