<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('Protobuf')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => [
            'imports_order' => [
                'const',
                'class',
                'function',
            ],
        ],
        'php_unit_fqcn_annotation' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
    ])
    ->setFinder($finder)
;

return $config;
