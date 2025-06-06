<?php

namespace PHPNomad\LeagueMarkdownIntegration\Strategies;

use InvalidArgumentException;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use League\HTMLToMarkdown\HtmlConverter;
use PHPNomad\Markdown\Exceptions\ConvertToHtmlException;
use PHPNomad\Markdown\Exceptions\ConvertToMarkdownException;
use PHPNomad\Markdown\Interfaces\CanConvertHtmlToMarkdown;
use PHPNomad\Markdown\Interfaces\CanConvertMarkdownToHtml;
use RuntimeException;

class MarkdownConversionStrategy implements CanConvertHtmlToMarkdown, CanConvertMarkdownToHtml
{
    private $markdownConverter;
    private $htmlConverter;

    public function __construct()
    {
        $this->markdownConverter = new CommonMarkConverter();
        $this->htmlConverter = new HtmlConverter();
    }

    public function toHtml(string $markdown): string
    {
        try {
            return $this->markdownConverter->convert($markdown);
        } catch (CommonMarkException $e) {
            throw new ConvertToHtmlException('Failed to convert markdown to HTML: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function toMarkdown(string $html): string
    {
        try {
            return $this->htmlConverter->convert($html);
        }catch(InvalidArgumentException|RuntimeException $e){
            throw new ConvertToMarkdownException('Failed to convert markdown to HTML: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}