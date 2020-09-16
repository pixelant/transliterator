<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Transliterator',
    'description' => 'Better transliteration in TYPO3 slugs, etc.',
    'version' => '0.1.0-alpha.7',
    'category' => 'fe',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.20-10.4.99',
            'php' => '7.2.0-7.4.99'
        ],
        'conflicts' => [],
    ],
    'state' => 'alpha',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Pixelant.net',
    'author_email' => 'info@pixelant.net',
    'author_company' => 'Pixelant.net',
    'autoload' => [
        'psr-4' => [
            'Pixelant\\Transliterator\\' => 'Classes'
        ],
    ],
];
