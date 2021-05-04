# Better Transliteration in TYPO3

Better transliteration in TYPO3 slugs, file names, etc. The implementation could be said to be
somewhat dirty, so please use with care.

This extension makes heavy use of the AsciiSlug class in symfony/string. This implementation uses 
the page language to determine how to transliterate the string, so & can be transliterated as "og" 
in Norwegian and "und" in German. Of course, as current language information isn't available 
everywhere in TYPO3, this only works where we can detect the language.
