<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\MathTrig;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;

class RomanTest extends AllSetupTeardown
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerROMAN')]
    public function testROMAN(string $expectedResult, string $formula): void
    {
        $this->mightHaveException($expectedResult);
        $sheet = $this->getSheet();
        $sheet->setCellValue('A3', 49);
        $sheet->getCell('A1')->setValue("=ROMAN($formula)");
        $result = $sheet->getCell('A1')->getCalculatedValue();
        self::assertEquals($expectedResult, $result);
    }

    public static function providerROMAN(): array
    {
        return require 'tests/data/Calculation/MathTrig/ROMAN.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerRomanArray')]
    public function testRomanArray(array $expectedResult, string $values, string $styles): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=ROMAN({$values}, {$styles})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEqualsWithDelta($expectedResult, $result, 1.0e-14);
    }

    public static function providerRomanArray(): array
    {
        return [
            'row vector' => [[['XLIX', 'MMXXII', 'CDXCIX']], '{49, 2022, 499}', '0'],
            'column vector' => [[['XLIX'], ['MMXXII'], ['CDXCIX']], '{49; 2022; 499}', '0'],
            'matrix' => [[['XLIX', 'MMXXII'], ['LXIV', 'CDXCIX']], '{49, 2022; 64, 499}', '0'],
        ];
    }
}
