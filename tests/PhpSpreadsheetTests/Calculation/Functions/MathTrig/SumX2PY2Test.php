<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\MathTrig;

use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PHPUnit\Framework\Attributes\DataProvider;

class SumX2PY2Test extends AllSetupTeardown
{
    /**
     * @param mixed[] $matrixData1
     * @param mixed[] $matrixData2
     */
    #[DataProvider('providerSUMX2PY2')]
    public function testSUMX2PY2(mixed $expectedResult, array $matrixData1, array $matrixData2): void
    {
        $this->mightHaveException($expectedResult);
        $sheet = $this->getSheet();
        $maxRow = 0;
        $funcArg1 = '';
        foreach (Functions::flattenArray($matrixData1) as $arg) {
            ++$maxRow;
            $funcArg1 = "A1:A$maxRow";
            $this->setCell("A$maxRow", $arg);
        }
        $maxRow = 0;
        $funcArg2 = '';
        foreach (Functions::flattenArray($matrixData2) as $arg) {
            ++$maxRow;
            $funcArg2 = "C1:C$maxRow";
            $this->setCell("C$maxRow", $arg);
        }
        $sheet->getCell('B1')->setValue("=SUMX2PY2($funcArg1, $funcArg2)");
        $result = $sheet->getCell('B1')->getCalculatedValue();
        self::assertEqualsWithDelta($expectedResult, $result, 1E-12);
    }

    public static function providerSUMX2PY2(): array
    {
        return require 'tests/data/Calculation/MathTrig/SUMX2PY2.php';
    }
}
