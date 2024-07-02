# Pimcore GPT Translations Bundle

Use GPT to translate in Pimcore

## Features

- **Objects:**  Translate your objects' fields into multiple languages.

### Dependencies

| Release | Supported Pimcore Versions | Supported Symfony Versions | Maintained     | Branch |
|---------|----------------------------|----------------------------|----------------|--------|
| **1.x** | `11.0`                     | `6.2`                      | Feature Branch | master |

## Installation

You can install the package via composer:

```bash
composer require ntriga/pimcore-gpt-translations
```

Add Bundle to `bundles.php`:

```php
return [
    OpenAI\Symfony\OpenAIBundle::class => ['all' => true],
    Ntriga\PimcoreGPTTranslations\PimcoreGPTTranslationsBundle::class => ['all' => true],
];
```

Add your GPT API key to your `.env.local` file:

```dotenv
OPENAI_API_KEY=sk-you-openai-api-key
```

## Default configuration
Default configuration for the bundle can look like this:

```yaml
parameters:
    gtp_translations_source_lang: 'nl'
    gtp_translations_target_langs:
        - 'fr'
```

## Further configuration
For more information about the setup, check [Setup](./docs/00_Setup.md)


## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
- [All contributors](../../contributors)

## License
GNU General Public License version 3 (GPLv3). Please see [License File](./LICENSE.md) for more information.

