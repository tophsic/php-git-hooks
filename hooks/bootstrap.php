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

$autoloadFiles = array(
    __DIR__ . '/../../../../vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
);

foreach ($autoloadFiles as $file) {
    if (file_exists($file)) {
        define('PHPGITHOOKS_COMPOSER_INSTALL', $file);
        require $file;
        break;
    }
}

unset($binDir, $home, $autoloadFiles, $file);

if (false === defined('PHPGITHOOKS_COMPOSER_INSTALL')) {
    fwrite(
        STDERR,
        'Composer autoload not found, you can submit an issue and blame @tophsic ;), see PR #30'
    );

    die(1);
}

require PHPGITHOOKS_COMPOSER_INSTALL;
