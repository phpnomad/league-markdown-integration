# phpnomad/league-markdown-integration

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/league-markdown-integration.svg)](https://packagist.org/packages/phpnomad/league-markdown-integration)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/league-markdown-integration.svg)](https://packagist.org/packages/phpnomad/league-markdown-integration)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/league-markdown-integration.svg)](https://packagist.org/packages/phpnomad/league-markdown-integration)
[![License](https://img.shields.io/packagist/l/phpnomad/league-markdown-integration.svg)](https://packagist.org/packages/phpnomad/league-markdown-integration)

Integrates [League CommonMark](https://commonmark.thephpleague.com) and [league/html-to-markdown](https://github.com/thephpleague/html-to-markdown) with PHPNomad's `phpnomad/markdown` abstraction. It ships a single strategy class that satisfies both conversion contracts so your application can bind the interfaces in its container and stay unaware of the underlying library.

## Installation

```bash
composer require phpnomad/league-markdown-integration
```

Composer pulls in `phpnomad/markdown` and both League libraries as transitive dependencies.

## What This Provides

`PHPNomad\LeagueMarkdownIntegration\Strategies\MarkdownConversionStrategy` is the only class in the package. It does three things.

- Implements both `CanConvertMarkdownToHtml` and `CanConvertHtmlToMarkdown` from `phpnomad/markdown`, so one binding covers both directions.
- `toHtml()` delegates to `League\CommonMark\CommonMarkConverter` and rethrows `CommonMarkException` as `ConvertToHtmlException`.
- `toMarkdown()` delegates to `League\HTMLToMarkdown\HtmlConverter` and rethrows `InvalidArgumentException` and `RuntimeException` as `ConvertToMarkdownException`.

Both League converters are constructed with their defaults. If you need custom CommonMark extensions or a non-default HTML-to-markdown configuration, write your own strategy class implementing the same interfaces and bind that instead.

## Requirements

- `phpnomad/markdown` for the conversion interfaces and exception hierarchy.
- `league/commonmark ^2.7` and `league/html-to-markdown ^5.1` for the actual conversion work. Both come in through Composer automatically.

## Usage

Register the strategy against both interfaces in a PHPNomad initializer so anything type-hinting `CanConvertMarkdownToHtml` or `CanConvertHtmlToMarkdown` resolves to the same implementation.

```php
<?php

namespace MyApp\Content;

use PHPNomad\LeagueMarkdownIntegration\Strategies\MarkdownConversionStrategy;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Markdown\Interfaces\CanConvertHtmlToMarkdown;
use PHPNomad\Markdown\Interfaces\CanConvertMarkdownToHtml;

final class Initializer implements HasClassDefinitions
{
    public function getClassDefinitions(): array
    {
        return [
            MarkdownConversionStrategy::class => [
                CanConvertMarkdownToHtml::class,
                CanConvertHtmlToMarkdown::class,
            ],
        ];
    }
}
```

Consumers type-hint the interfaces, not `MarkdownConversionStrategy` itself. Swapping implementations later is a one-line change in this initializer.

## Documentation

Full PHPNomad documentation lives at [phpnomad.com](https://phpnomad.com). For the underlying libraries, see the [CommonMark](https://commonmark.thephpleague.com) and [html-to-markdown](https://github.com/thephpleague/html-to-markdown) project pages.

## License

MIT. See [LICENSE](LICENSE).
