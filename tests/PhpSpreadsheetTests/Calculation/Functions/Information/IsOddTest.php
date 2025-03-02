<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Information;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;
use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;
use PHPUnit\Framework\TestCase;

class IsOddTest extends TestCase
{
    public function testIsOddNoArgument(): void
    {
        $result = Value::isOdd();
        self::assertSame(ExcelError::NAME(), $result);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerIsOdd')]
    public function testIsOdd(bool|string $expectedResult, mixed $value): void
    {
        $result = Value::isOdd($value);
        self::assertEquals($expectedResult, $result);
    }

    public static function providerIsOdd(): array
    {
        return require 'tests/data/Calculation/Information/IS_ODD.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerIsOddArray')]
    public function testIsOddArray(array $expectedResult, string $values): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=ISODD({$values})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEquals($expectedResult, $result);
    }

    public static function providerIsOddArray(): array
    {
        return [
            'vector' => [
                [[false, true, false, true, false]],
                '{-2, -1, 0, 1, 2}',
            ],
        ];
    }
}
