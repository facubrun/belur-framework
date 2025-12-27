<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();

return $config->setFinder($finder)->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'braces_position' => [
        'functions_opening_brace' => 'same_line',
        'classes_opening_brace' => 'same_line',
    ],
]);

