<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Calculation\Functions\LookupRef;

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PHPUnit\Framework\Attributes\DataProvider;

class OffsetTest extends AllSetupTeardown
{
    #[DataProvider('providerOFFSET')]
    public function testOFFSET(mixed $expectedResult, null|string $cellReference = null): void
    {
        $result = LookupRef\Offset::OFFSET($cellReference);
        self::assertSame($expectedResult, $result);
    }

    public static function providerOFFSET(): array
    {
        return require 'tests/data/Calculation/LookupRef/OFFSET.php';
    }

    public function testOffsetSpreadsheet(): void
    {
        $sheet = $this->getSheet();
        $sheet->getCell('B6')->setValue(4);
        $sheet->getCell('B7')->setValue(8);
        $sheet->getCell('B8')->setValue(3);
        $sheet->getCell('D6')->setValue(10);
        $sheet->getCell('D7')->setValue(3);
        $sheet->getCell('D8')->setValue(6);

        $sheet->getCell('A1')->setValue('=OFFSET(D3,3,-2,1,1)');
        self::assertSame(4, $sheet->getCell('A1')->getCalculatedValue());
        $sheet->getCell('A2')->setValue('=SUM(OFFSET(D3:F5,3,-2, 3, 3))');
        self::assertSame(34, $sheet->getCell('A2')->getCalculatedValue());
        $sheet->getCell('A3')->setValue('=OFFSET(D3, -3, -3)');
        self::assertSame('#REF!', $sheet->getCell('A3')->getCalculatedValue());

        $sheet->getCell('C1')->setValue(5);
        $sheet->getCell('A4')->setValue('=OFFSET(C1, 0, 0, 0, 0)');
        self::assertSame('#REF!', $sheet->getCell('A4')->getCalculatedValue());
        $sheet->getCell('A5')->setValue('=OFFSET(C1, 0, 0)');
        self::assertSame(5, $sheet->getCell('A5')->getCalculatedValue());
    }

    public function testOffsetNamedRange(): void
    {
        $workSheet = $this->getSheet();
        $workSheet->setCellValue('A1', 1);
        $workSheet->setCellValue('A2', 2);

        $this->getSpreadsheet()->addNamedRange(new NamedRange('demo', $workSheet, '=$A$1'));

        $workSheet->setCellValue('B1', '=demo');
        $workSheet->setCellValue('B2', '=OFFSET(demo, 1, 0)');

        self::assertSame(2, $workSheet->getCell('B2')->getCalculatedValue());
    }

    public function testOffsetNamedRangeApostropheSheet(): void
    {
        $workSheet = $this->getSheet();
        $workSheet->setTitle("apo'strophe");
        $workSheet->setCellValue('A1', 1);
        $workSheet->setCellValue('A2', 2);

        $this->getSpreadsheet()->addNamedRange(new NamedRange('demo', $workSheet, '=$A$1'));

        $workSheet->setCellValue('B1', '=demo');
        $workSheet->setCellValue('B2', '=OFFSET(demo, 1, 0)');

        self::assertSame(2, $workSheet->getCell('B2')->getCalculatedValue());
    }

    public function testOffsetMultiCellNamedRange(): void
    {
        $sheet = $this->getSheet();
        $sheet->setCellValue('D13', 'Hello');
        $this->getSpreadsheet()
            ->addNamedRange(new NamedRange('CELLAREA', $sheet, '$B$6:$F$22'));
        $sheet->setCellValue('D1', '=OFFSET(CELLAREA,7,2,1,1)');
        self::assertSame('Hello', $sheet->getCell('D1')->getCalculatedValue());
    }
}
