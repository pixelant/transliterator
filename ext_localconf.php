<?php

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Charset\CharsetConverter::class] = [
    'className' => \Pixelant\Transliterator\Charset\CharsetConverter::class
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\DataHandling\SlugHelper::class] = [
    'className' => \Pixelant\Transliterator\DataHandling\SlugHelper::class
];
