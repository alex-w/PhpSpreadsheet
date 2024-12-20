<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Statistical;

class StDevTest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerSTDEV')]
    public function testSTDEV(mixed $expectedResult, mixed ...$args): void
    {
        $this->runTestCaseReference('STDEV', $expectedResult, ...$args);
    }

    public static function providerSTDEV(): array
    {
        return require 'tests/data/Calculation/Statistical/STDEV.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerOdsSTDEV')]
    public function testOdsSTDEV(mixed $expectedResult, mixed ...$args): void
    {
        $this->setOpenOffice();
        $this->runTestCaseReference('STDEV', $expectedResult, ...$args);
    }

    public static function providerOdsSTDEV(): array
    {
        return require 'tests/data/Calculation/Statistical/STDEV_ODS.php';
    }
}
