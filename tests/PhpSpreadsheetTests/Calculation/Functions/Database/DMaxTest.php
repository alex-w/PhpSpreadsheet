<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Database;

use PhpOffice\PhpSpreadsheet\Calculation\Database\DMax;
use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;
use PHPUnit\Framework\Attributes\DataProvider;

class DMaxTest extends SetupTeardownDatabases
{
    /**
     * @param mixed[] $database
     * @param mixed[][] $criteria
     */
    #[DataProvider('providerDMax')]
    public function testDirectCallToDMax(int|string $expectedResult, array $database, string|null|int $field, array $criteria): void
    {
        $result = DMax::evaluate($database, $field, $criteria);
        self::assertEqualsWithDelta($expectedResult, $result, 1.0e-12);
    }

    /**
     * @param mixed[] $database
     * @param mixed[][] $criteria
     */
    #[DataProvider('providerDMax')]
    public function testDMaxAsWorksheetFormula(int|string $expectedResult, array $database, string|null|int $field, array $criteria): void
    {
        $this->prepareWorksheetWithFormula('DMAX', $database, $field, $criteria);

        $result = $this->getSheet()->getCell(self::RESULT_CELL)->getCalculatedValue();
        self::assertEqualsWithDelta($expectedResult, $result, 1.0e-12);
    }

    public static function providerDMax(): array
    {
        return [
            [
                96,
                self::database1(),
                'Profit',
                [
                    ['Tree', 'Height', 'Height'],
                    ['=Apple', '>10', '<16'],
                    ['=Pear', null, null],
                ],
            ],
            [
                340000,
                self::database2(),
                'Sales',
                [
                    ['Quarter', 'Area'],
                    [2, 'North'],
                ],
            ],
            [
                460000,
                self::database2(),
                'Sales',
                [
                    ['Sales Rep.', 'Quarter'],
                    ['Carol', '>1'],
                ],
            ],
            'omitted field name' => [
                ExcelError::VALUE(),
                self::database1(),
                null,
                self::database1(),
            ],
            'field column number okay' => [
                18,
                self::database1(),
                2,
                self::database1(),
            ],
            /* Excel seems to return #NAME? when column number
               is too high or too low. This makes so little sense
               to me that I'm not going to bother coding that up,
               content to return #VALUE! as an invalid name would */
            'field column number too high' => [
                ExcelError::VALUE(),
                self::database1(),
                99,
                self::database1(),
            ],
            'field column number too low' => [
                ExcelError::VALUE(),
                self::database1(),
                0,
                self::database1(),
            ],
        ];
    }
}
