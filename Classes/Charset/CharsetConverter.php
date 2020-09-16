<?php

namespace Pixelant\Transliterator\Charset;

use Pixelant\Transliterator\Utility\LocalizationUtility;
use Symfony\Component\String\Slugger\AsciiSlugger;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CharsetConverter extends \TYPO3\CMS\Core\Charset\CharsetConverter
{
    public function specCharsToASCII($charset, $string)
    {
        if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem']) {
            return parent::specCharsToASCII($charset, $string);
        }

        $typo3Language = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('transliterator', 'defaultLanguage') ?? 'default';

        $enableSymbolsMap = GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get('transliterator', 'enableSymbolsMap') ?? false;

        $locale = LocalizationUtility::typo3LanguageCodeToIso($typo3Language);

        /** @var AsciiSlugger $slugger */
        $slugger = GeneralUtility::makeInstance(
            AsciiSlugger::class,
            $locale,
            $enableSymbolsMap ? LocalizationUtility::getSymbolsMap($typo3Language) : []
        );

        $parts = explode('.', $string);
        $slugParts = [];
        foreach ($parts as $part) {
            $slugParts[] = $slugger->slug($part);
        }

        $slug = implode('.', ArrayUtility::removeArrayEntryByValue($slugParts, ''));

        return $slug;
    }
}
