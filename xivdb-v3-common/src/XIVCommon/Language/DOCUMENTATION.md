# Language Library

```php
LanguageList::LANGUAGES
```

Provides a list of supported languages


```php
LanguageList::DEFAULT
```

Provides the default language


```php
LanguageConverter::handle( $object, $language = LanguageList::DEFAULT )
```

Convert an object into post-fix-less language specified fields, for example:

- `Name_de` --> `Name` in German.
