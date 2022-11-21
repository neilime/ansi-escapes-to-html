<?php

namespace TestSuite;

// Composer autoloading
if (!file_exists($composerAutoloadPath = __DIR__ . '/../vendor/autoload.php')) {
    throw new \RuntimeException('Composer autoload file "' . $composerAutoloadPath . '" does not exist');
}
if (false === (include $composerAutoloadPath)) {
    throw new \RuntimeException(
        'An error occured while including composer autoload file "' . $composerAutoloadPath . '"'
    );
}
