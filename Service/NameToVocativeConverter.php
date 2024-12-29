<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle\Service;

use Granam\CzechVocative\CzechName;
use MauticPlugin\GranamCzechVocativeBundle\Service\Helpers\NameToVocativeOptions;
use MauticPlugin\GranamCzechVocativeBundle\Service\Helpers\RecursiveImplode;

class NameToVocativeConverter
{
    public const SERVICE_ID = 'plugin.vocative.name_converter';

    private CzechName $name;

    public function __construct(CzechName $name)
    {
        $this->name = $name;
    }

    public function toVocative(string $name, ?NameToVocativeOptions $options = null): string
    {
        if ($options !== null) {
            if ($name === '' && $options->hasEmptyNameAlias()) {
                $name = (string)$options->getEmptyNameAlias();
            } elseif ($options->hasMaleAlias() && $this->name->isMale($name)) {
                $name = (string)$options->getMaleAlias();
            } elseif ($options->hasFemaleAlias() && !$this->name->isMale($name)) {
                $name = (string)$options->getFemaleAlias();
            }
        }

        if ($name === '') {
            return '';
        }

        $decodedName = html_entity_decode($name);

        return $decodedName === $name
            ? $this->name->vocative($name)
            : htmlentities($this->name->vocative($decodedName));
    }

    /**
     * Searches for [name|vocative] and replaces it with the vocative form.
     *
     * @param string $value
     * @return array<string, string>
     */
    public function findAndReplace(string $value): array
    {
        $regexpParts = [
            '(?<toReplace>',
            [
                '(?:\[|%5B)', // Opening bracket, native or URL encoded
                [
                    '\s*', // Trim leading whitespace
                    '(', // Group for possible formats
                    [
                        [ // First format: enclosed by brackets
                            '[\[]\s*',
                            '(?<toVocative1>(?:[^\[\]]*[^\s\[\]]|))', // No brackets inside
                            '\s*[\]]',
                        ],
                        '|', // Or
                        [ // Second format: without enclosing brackets
                            '(?<toVocative2>(?:[^\[\]]*[^\s\[\]]|))',
                        ],
                    ],
                    ')',
                    '\s*', // Trim trailing whitespace
                    '\|\s*vocative\s*', // Pipe and "vocative" keyword
                    '(?:\(+', // Options in parentheses
                    [
                        '\s*',
                        '(?<options>[^\)\]]*[^\)\]\s])?', // Optional options
                        '\s*',
                    ],
                    '\)*)?', // Optional closing parenthesis
                    '\s*', // Trim trailing whitespace
                ],
                '(?:\]|%5D)', // Closing bracket, native or URL encoded
            ],
            ')',
        ];

        $regexp = '~' . RecursiveImplode::implode($regexpParts) . '~u'; // UTF-8
        $tokens = [];

        /** @var array<string, array<string>> $matches */
        if (preg_match_all($regexp, $value, $matches) > 0) {
            foreach ($matches['toReplace'] as $index => $toReplace) {
                $toVocative = $matches['toVocative1'][$index] !== ''
                    ? $matches['toVocative1'][$index]
                    : $matches['toVocative2'][$index];

                $stringOptions = $matches['options'][$index] ?? '';
                $tokens[$toReplace] = $this->toVocative($toVocative, NameToVocativeOptions::createFromString($stringOptions));
            }
        }

        return $tokens;
    }
}
