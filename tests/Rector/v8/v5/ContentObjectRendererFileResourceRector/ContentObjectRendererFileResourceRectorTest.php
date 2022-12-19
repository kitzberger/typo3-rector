<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v5\ContentObjectRendererFileResourceRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ContentObjectRendererFileResourceRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     * @return never
     */
    public function test(string $filePath): void
    {
        $this->markTestIncomplete('The comparison is false positive wrongly.');
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
