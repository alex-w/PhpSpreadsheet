<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Chart;

use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Exception;
use PHPUnit\Framework\TestCase;

class DataSeriesValuesTest extends TestCase
{
    public function testSetDataType(): void
    {
        $dataTypeValues = [
            'Number',
            'String',
        ];

        $testInstance = new DataSeriesValues();

        foreach ($dataTypeValues as $dataTypeValue) {
            $result = $testInstance->setDataType($dataTypeValue);
            self::assertSame($dataTypeValue, $result->getDataType());
        }
    }

    public function testSetInvalidDataTypeThrowsException(): void
    {
        $testInstance = new DataSeriesValues();

        try {
            $testInstance->setDataType('BOOLEAN');
        } catch (Exception $e) {
            self::assertEquals($e->getMessage(), 'Invalid datatype for chart data series values');

            return;
        }
        self::fail('An expected exception has not been raised.');
    }

    public function testGetDataType(): void
    {
        $dataTypeValue = 'String';

        $testInstance = new DataSeriesValues();
        $testInstance->setDataType($dataTypeValue);

        $result = $testInstance->getDataType();
        self::assertEquals($dataTypeValue, $result);
    }

    public function testGetLineWidth(): void
    {
        $testInstance = new DataSeriesValues();
        // default has changed to null from 1 point (12700)
        self::assertNull($testInstance->getLineWidth(), 'should have default');

        $testInstance->setLineWidth(40000 / Properties::POINTS_WIDTH_MULTIPLIER);
        self::assertEquals(40000 / Properties::POINTS_WIDTH_MULTIPLIER, $testInstance->getLineWidth());

        $testInstance->setLineWidth(1);
        self::assertEquals(12700 / Properties::POINTS_WIDTH_MULTIPLIER, $testInstance->getLineWidth(), 'should enforce minimum width');
    }

    public function testFillColorCorrectInput(): void
    {
        $testInstance = new DataSeriesValues();

        self::assertEquals($testInstance, $testInstance->setFillColor('00abb8'));
        self::assertEquals($testInstance, $testInstance->setFillColor(['00abb8', 'b8292f']));
    }

    public function testFillColorInvalidInput(): void
    {
        $testInstance = new DataSeriesValues();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid hex color for chart series');

        $testInstance->setFillColor('WRONG COLOR');
    }

    public function testFillColorInvalidInputInArray(): void
    {
        $testInstance = new DataSeriesValues();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid hex color for chart series (color: "WRONG COLOR")');

        $testInstance->setFillColor(['b8292f', 'WRONG COLOR']);
    }
}
