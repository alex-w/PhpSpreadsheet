<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Statistical;

class CovarTest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerCOVAR')]
    public function testCOVAR(mixed $expectedResult, mixed ...$args): void
    {
        $this->runTestCaseNoBracket('COVAR', $expectedResult, ...$args);
    }

    public static function providerCOVAR(): array
    {
        return require 'tests/data/Calculation/Statistical/COVAR.php';
    }

    public function testMultipleRows(): void
    {
        $sheet = $this->getSheet();
        $sheet->fromArray([
            [1, 2],
            [3, 4],
            [5, 6],
            [7, 8],
        ]);
        $sheet->getCell('Z99')->setValue('=COVAR(A1:B2,A3:B4)');
        self::assertSame(1.25, $sheet->getCell('Z99')->getCalculatedValue());
    }
}
