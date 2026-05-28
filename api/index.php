<?php
$compiledViewPath = '/tmp/storage/framework/views';
if (!is_dir($compiledViewPath)) {
    mkdir($compiledViewPath, 0777, true);
}
$_ENV['VIEW_COMPILED_PATH'] = $compiledViewPath;
putenv("VIEW_COMPILED_PATH={$compiledViewPath}");

require __DIR__ . '/../public/index.php';
