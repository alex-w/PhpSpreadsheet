<?php

declare(strict_types=1);

namespace PhpOffice\PhpSpreadsheetTests\Worksheet;

use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PHPUnit\Framework\TestCase;

class PageMarginsTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('providerPointsAndInches')]
    public function testPointsToInches(float $value, float $expectedResult): void
    {
        $actualResult = PageMargins::fromPoints($value);
        self::assertSame($expectedResult, $actualResult);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerPointsAndInches')]
    public function testInchesToPoints(float $expectedResult, float $value): void
    {
        $actualResult = PageMargins::toPoints($value);
        self::assertSame($expectedResult, $actualResult);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerCentimetersAndInches')]
    public function testCentimetersToInches(float $value, float $expectedResult): void
    {
        $actualResult = PageMargins::fromCentimeters($value);
        self::assertSame($expectedResult, $actualResult);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerCentimetersAndInches')]
    public function testPointsToCentimeters(float $expectedResult, float $value): void
    {
        $actualResult = PageMargins::toCentimeters($value);
        self::assertSame($expectedResult, $actualResult);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerMillimetersAndInches')]
    public function testMillimetersToInches(float $value, float $expectedResult): void
    {
        $actualResult = PageMargins::fromMillimeters($value);
        self::assertSame($expectedResult, $actualResult);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('providerMillimetersAndInches')]
    public function testPointsToMillimeters(float $expectedResult, float $value): void
    {
        $actualResult = PageMargins::toMillimeters($value);
        self::assertSame($expectedResult, $actualResult);
    }

    public static function providerPointsAndInches(): array
    {
        return [
            [36, 0.5],
            [72, 1.0],
            [90, 1.25],
            [144, 2.0],
        ];
    }

    public static function providerCentimetersAndInches(): array
    {
        return [
            [1.27, 0.5],
            [2.54, 1.0],
        ];
    }

    public static function providerMillimetersAndInches(): array
    {
        return [
            [12.7, 0.5],
            [25.4, 1.0],
        ];
    }
}
