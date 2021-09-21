<?php

namespace IDM\LaravelHtmlValidator\Contracts;

interface HtmlValidatorInterface
{
    /**
     * HtmlValidatorInterface constructor.
     * @param string $url
     */
    public function __construct(string $url);

    /**
     * Set URL to validate
     * @param string $url
     */
    public function setUrl(string $url): void;

    /**
     * Generating link to validator
     * @return string
     */
    public function getLink(): string;

    /**
     * Return validation results
     * @return array
     */
    public function validate(): array;
}
