<?php

namespace Pixelant\Transliterator\Utility;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class LocalizationUtility
{
    protected const SYMBOL_MAPPING_FILE_PATH = 'EXT:transliterator/Resources/Private/Language/symbolMapping.xlf';

    /**
     * @param string $typo3Language TYPO3 language code
     * @return array
     */
    public static function getSymbolsMap(string $typo3Language): array
    {
        /** @var LanguageService $languageService */
        $languageService = GeneralUtility::makeInstance(LanguageService::class);
        $languageService->init($typo3Language);

        if (
            VersionNumberUtility::convertVersionNumberToInteger(
                VersionNumberUtility::getCurrentTypo3Version()
            ) < 10000000
        ) {
            $symbolLabels = $languageService->includeLLFile(self::SYMBOL_MAPPING_FILE_PATH, false);
        } else {
            $symbolLabels = $languageService->includeLLFile(self::SYMBOL_MAPPING_FILE_PATH);
        }

        $symbolsMap = [];
        foreach (array_pop($symbolLabels) ?? [] as $symbol => $value) {
            $symbolsMap[$symbol] = ($value[0]['target'] ?? $value[0]['source']) ?? '';
        }

        return $symbolsMap;
    }

    /**
     * Converts the supplied TYPO3 language code to ISO code
     *
     * @param string $typo3Language
     * @return string Correct iso code or original code as fallback option
     */
    public static function typo3LanguageCodeToIso(string $typo3Language): string
    {
        if ($typo3Language === 'default') {
            return 'en';
        }

        if (
            VersionNumberUtility::convertVersionNumberToInteger(
                VersionNumberUtility::getCurrentTypo3Version()
            ) < 10000000
        ) {
            /** @var Locales $locales */
            $locales = Locales::initialize();
        } else {
            /** @var Locales $locales */
            $locales = GeneralUtility::makeInstance(Locales::class);
        }

        if (in_array($typo3Language, $locales->getLocales())) {
            return $typo3Language;
        }

        return $locales->getIsoMapping()[$typo3Language] ?? $typo3Language;
    }
}
