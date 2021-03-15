<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Conditions;

use Nette\Utils\Strings;

final class HostnameConditionMatcher implements TyposcriptConditionMatcher
{
    /**
     * @var string
     */
    private const TYPE = 'hostname';

    public function change(string $condition): ?string
    {
        return $condition;
    }

    public function shouldApply(string $condition): bool
    {
        return Strings::startsWith($condition, self::TYPE);
    }
}
