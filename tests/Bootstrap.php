<?php

namespace TestSuite;

chdir(dirname(__DIR__));

// Composer autoloading
if (!file_exists($sComposerAutoloadPath = __DIR__ . '/../vendor/autoload.php')) {
    throw new \RuntimeException('Composer autoload file "' . $sComposerAutoloadPath . '" does not exist');
}
if (false === (include $sComposerAutoloadPath)) {
    throw new \RuntimeException('An error occured while including composer autoload file "' . $sComposerAutoloadPath . '"');
}