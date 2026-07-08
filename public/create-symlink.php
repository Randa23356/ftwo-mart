<?php

$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo 'Symlink already exists.';
    exit;
}

if (symlink($target, $link)) {
    echo 'Symlink created successfully!';
} else {
    echo 'Failed to create symlink. You may need to ask your hosting provider.';
}
