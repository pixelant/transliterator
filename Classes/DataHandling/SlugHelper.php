<?php

namespace Pixelant\Transliterator\DataHandling;

use Symfony\Component\String\Slugger\AsciiSlugger;
use TYPO3\CMS\Core\Configuration\SiteConfiguration;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SlugHelper extends \TYPO3\CMS\Core\DataHandling\SlugHelper
{
    protected const SYMBOL_MAPPING_FILE_PATH = 'EXT:transliterator/Resources/Private/Language/symbolMapping.xlf';

    /**
     * @var SiteLanguage
     */
    protected $language = null;

    public function sanitize(string $slug): string
    {
        if ($slug === '' || $slug === '/' || $slug === '//') {
            return $this->prependSlashInSlug ? '/' : '';
        }

        $fallbackCharacter = $this->configuration['fallbackCharacter'] ?? '-';

        $slug = strip_tags($slug);

        $parts = explode('/', $slug);

        $locale = null;
        $symbolsMap = [];
        if ($this->language !== null) {
            $locale = $this->language->getLocale();
            $symbolsMap = [$locale => $this->getSymbolsMap()];
        }

        /** @var AsciiSlugger $slugger */
        $slugger = GeneralUtility::makeInstance(AsciiSlugger::class, $locale, $symbolsMap);

        foreach ($parts as &$part) {
            $part = $slugger->slug(
                $part,
                $fallbackCharacter
            )->lower();
        }

        $slug = implode('/', $parts);

        if ($this->prependSlashInSlug && ($slug[0] ?? '') !== '/') {
            $slug = '/' . $slug;
        }

        if (!$this->prependSlashInSlug && ($slug[0] ?? '') === '/') {
            $slug = substr($slug, 1);
        }

        $slug = preg_replace('/\\/+$/', '/', $slug);

        return $slug;
    }

    public function generate(array $recordData, int $pid): string
    {
        $languageFieldName = $GLOBALS['TCA'][$this->tableName]['ctrl']['languageField'] ?? null;
        $languageId = (int)($recordData[$languageFieldName] ?? 0);

        try {
            /** @var Site $site */
            $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($pid);

            $this->language = $site->getLanguageById($languageId);
        } catch (\ArgumentCountError $error) {
            $this->language = null;
        }


        return parent::generate($recordData, $pid);
    }

    protected function getSymbolsMap(): array
    {
        /** @var LanguageService $languageService */
        $languageService = GeneralUtility::makeInstance(LanguageService::class);
        $languageService->init($this->language->getTypo3Language());

        $symbolLabels = $languageService->includeLLFile(static::SYMBOL_MAPPING_FILE_PATH);

        $symbolsMap = [];
        foreach (array_pop($symbolLabels) ?? [] as $symbol => $value) {
            $symbolsMap[$symbol] = ($value[0]['target'] ?? $value[0]['source']) ?? '';
        }

        return $symbolsMap;
    }
}
