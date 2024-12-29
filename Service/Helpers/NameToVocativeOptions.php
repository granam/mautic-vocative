<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle\Service\Helpers;

use MauticPlugin\GranamCzechVocativeBundle\Service\Helpers\Exceptions\UnknownOption;

class NameToVocativeOptions
{
    private ?string $maleAlias = null;
    private ?string $femaleAlias = null;
    private ?string $emptyNameAlias = null;

    public static function createFromString(string $stringOptions): self
    {
        $options = [];
        $stringOptions = trim($stringOptions);

        if ($stringOptions !== '') {
            $values = explode(',', $stringOptions);

            $options['maleAlias'] = trim($values[0] ?? '') ?: null;
            $options['femaleAlias'] = trim($values[1] ?? '') ?: null;
            $options['emptyNameAlias'] = trim($values[2] ?? '') ?: null;
        }

        return new self($options);
    }

    /**
     * @param array<string, ?string> $values
     * @throws UnknownOption
     */
//    public function __construct(array $values)
    public function __construct(array $values = [])
    {
        foreach ($values as $name => $value) {
            match ($name) {
                'maleAlias' => $this->maleAlias = $value,
                'femaleAlias' => $this->femaleAlias = $value,
                'emptyNameAlias' => $this->emptyNameAlias = $value,
                default => throw new UnknownOption("Got unknown option: " . var_export($name, true)),
            };
        }
    }

    public function hasMaleAlias(): bool
    {
        return $this->maleAlias !== null;
    }

    public function getMaleAlias(): ?string
    {
        return $this->maleAlias;
    }

    public function hasFemaleAlias(): bool
    {
        return $this->femaleAlias !== null;
    }

    public function getFemaleAlias(): ?string
    {
        return $this->femaleAlias;
    }

    public function hasEmptyNameAlias(): bool
    {
        return $this->emptyNameAlias !== null;
    }

    public function getEmptyNameAlias(): ?string
    {
        return $this->emptyNameAlias;
    }
}
