<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Engineering;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Engineering\ComplexFunctions;
use PhpOffice\PhpSpreadsheet\Calculation\Exception as CalculationException;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheetTests\Calculation\Functions\FormulaArguments;
use PhpOffice\PhpSpreadsheetTests\Custom\ComplexAssert;
use PHPUnit\Framework\TestCase;

class ImExpTest extends TestCase
{
    const COMPLEX_PRECISION = (PHP_INT_SIZE > 4) ? 1E-12 : 1E-9;

    private ComplexAssert $complexAssert;

    protected function setUp(): void
    {
        Functions::setCompatibilityMode(Functions::COMPATIBILITY_EXCEL);
        $this->complexAssert = new ComplexAssert();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerIMEXP')]
    public function testDirectCallToIMEXP(string $expectedResult, string $arg): void
    {
        $result = ComplexFunctions::IMEXP($arg);
        self::assertTrue(
            $this->complexAssert->assertComplexEquals($expectedResult, $result, self::COMPLEX_PRECISION),
            $this->complexAssert->getErrorMessage()
        );
    }

    private function trimIfQuoted(string $value): string
    {
        return trim($value, '"');
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerIMEXP')]
    public function testIMEXPAsFormula(mixed $expectedResult, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $calculation = Calculation::getInstance();
        $formula = "=IMEXP({$arguments})";

        /** @var float|int|string */
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertTrue(
            $this->complexAssert->assertComplexEquals($expectedResult, $this->trimIfQuoted((string) $result), self::COMPLEX_PRECISION),
            $this->complexAssert->getErrorMessage()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerIMEXP')]
    public function testIMEXPInWorksheet(mixed $expectedResult, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $argumentCells = $arguments->populateWorksheet($worksheet);
        $formula = "=IMEXP({$argumentCells})";

        $result = $worksheet->setCellValue('A1', $formula)
            ->getCell('A1')
            ->getCalculatedValue();
        self::assertTrue(
            $this->complexAssert->assertComplexEquals($expectedResult, $result, self::COMPLEX_PRECISION),
            $this->complexAssert->getErrorMessage()
        );

        $spreadsheet->disconnectWorksheets();
    }

    public static function providerIMEXP(): array
    {
        return require 'tests/data/Calculation/Engineering/IMEXP.php';
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerUnhappyIMEXP')]
    public function testIMEXPUnhappyPath(string $expectedException, mixed ...$args): void
    {
        $arguments = new FormulaArguments(...$args);

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $argumentCells = $arguments->populateWorksheet($worksheet);
        $formula = "=IMEXP({$argumentCells})";

        $this->expectException(CalculationException::class);
        $this->expectExceptionMessage($expectedException);
        $worksheet->setCellValue('A1', $formula)
            ->getCell('A1')
            ->getCalculatedValue();

        $spreadsheet->disconnectWorksheets();
    }

    public static function providerUnhappyIMEXP(): array
    {
        return [
            ['Formula Error: Wrong number of arguments for IMEXP() function'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerImExpArray')]
    public function testImExpArray(array $expectedResult, string $complex): void
    {
        $calculation = Calculation::getInstance();

        $formula = "=IMEXP({$complex})";
        $result = $calculation->_calculateFormulaValue($formula);
        self::assertEquals($expectedResult, $result);
    }

    public static function providerImExpArray(): array
    {
        return [
            'row/column vector' => [
                [
                    ['-0.29472426558547-0.22016559792964i', '-0.80114361554693-0.59847214410396i', '-2.1777341321272-1.6268159541567i'],
                    ['0.19876611034641-0.30955987565311i', '0.54030230586814-0.8414709848079i', '1.4686939399159-2.2873552871788i'],
                    ['0.19876611034641+0.30955987565311i', '0.54030230586814+0.8414709848079i', '1.4686939399159+2.2873552871788i'],
                    ['-0.29472426558547+0.22016559792964i', '-0.80114361554693+0.59847214410396i', '-2.1777341321272+1.6268159541567i'],
                ],
                '{"-1-2.5i", "-2.5i", "1-2.5i"; "-1-i", "-i", "1-i"; "-1+i", "i", "1+1"; "-1+2.5i", "+2.5i", "1+2.5i"}',
            ],
        ];
    }
}
