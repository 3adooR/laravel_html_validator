<?php

namespace idm\LaravelHtmlValidator\Services;

use IDM\LaravelHtmlValidator\Contracts\HtmlValidatorInterface;

/**
 * Class HtmlValidator
 * @package IDM\LaravelHtmlValidator\Services
 */
class HtmlValidator implements HtmlValidatorInterface
{
    /** @var string - validated URL */
    public string $url = '';

    /**
     * HtmlValidator constructor.
     * @param string $url
     */
    public function __construct(string $url = '')
    {
        if (!empty($url)) {
            $this->setUrl($url);
        }
    }

    /**
     * Set URL to validate
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Generating link to validator
     * @return string
     */
    public function getLink(): string
    {
        return config('htmlvalidator.validator_url', 'https://validator.w3.org/unicorn/check')
            . '?ucn_task=' . config('htmlvalidator.validator_task', 'conformance')
            . '&ucn_uri=' . $this->url;
    }

    /**
     * Parse validator page and return results
     * @return array
     */
    public function validate(): array
    {
        $html = $this->parse();
        if (empty($html)) {
            return ['error' => 'can not get HTML source from ' . $this->getLink()];
        }
        return $this->result($html);
    }

    /**
     * Parse validator page, get response HTML
     * @param string $url
     * @return bool|string|void
     */
    private function parse()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getLink());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html',
            'Accept-Charset: utf-8'
        ]);
        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($responseCode !== 200) {
            return;
        }
        curl_close($ch);
        return $response;
    }

    /**
     * Making results from HTML response
     * @param string $html
     * @return array
     */
    private function result(string $html): array
    {
        $errors = $this->getErrors($html);
        $warnings = (config('htmlvalidator.ignore_warnings', true)) ? 0 : $this->getWarnings($html);
        $isValid = ($errors > 0 || $warnings > 0) ? false : true;
        $results = [
            'isValid' => $isValid,
            'errors' => $errors,
            'warnings' => $warnings,
            'link' => $this->getLink()
        ];
        if (config('htmlvalidator.return_html', true)) {
            $results['html'] = htmlspecialchars($html);
        }
        return $results;
    }

    /**
     * Count of errors
     * @param string $html
     * @return int
     */
    private function getErrors(string $html)
    {
        return $this->getCount('Errors', $html);
    }

    /**
     * Count of warnings
     * @param string $html
     * @return int
     */
    private function getWarnings(string $html)
    {
        return $this->getCount('Info', $html);
    }

    /**
     * Find count of errors and warnings
     * @param string $needle
     * @param string $html
     * @return int
     */
    private function getCount(string $needle, string $html): int
    {
        $count = 0;
        $needle .= ' (';
        if (stristr($html, $needle)) {
            $htmlParts = explode($needle, $html);
            if (!empty($htmlParts)) {
                $htmlEnd = $htmlParts[1];
                $htmlParts = explode(')', $htmlEnd);
                if (!empty($htmlParts)) {
                    $count = (int)trim($htmlParts[0]);
                }
            }
        }
        return $count;
    }
}
