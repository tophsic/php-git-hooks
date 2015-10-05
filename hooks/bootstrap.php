<?php

$binDir = 'bin';
$home = false;
if (false !== getenv('HOME')) {
    $home = getenv('HOME') . '/.composer';
}

if (false !== getenv('COMPOSER_HOME')) {
    $home = getenv('COMPOSER_HOME');
}

if (
    false !== $home &&
    0 !== preg_match('~' . $home . '~', __DIR__)
) {
    $binDir = $home . '/vendor/bin';
}

define('PHPGITHOOKS_BIN_DIR', $binDir);

require __DIR__ . '/../../../../vendor/autoload.php';
