# Better Transliteration in TYPO3

Better transliteration in TYPO3 slugs, file names, etc. The implementation could be said to be
somewhat dirty, so please use with care.

This extension makes heavy use of the AsciiSlug class in symfony/string. This implementation uses 
the page language to determine how to transliterate the string, so & can be transliterated as "og" 
in Norwegian and "und" in German. It will also transliterate "ö" as "oe" in German and "o" in 
Swedish and English. 

As current language information isn't available everywhere in TYPO3, language detection only works 
where we can detect the language. Luckily, you can set a default transliteration language in
the extension configuration "Default TYPO3 ASCII transliteration language" (`defaultLanguage`).

Related TYPO3 Forge issue: https://forge.typo3.org/issues/20612
