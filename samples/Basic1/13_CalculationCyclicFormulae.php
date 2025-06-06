<?php

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require __DIR__ . '/../Header.php';
/** @var PhpOffice\PhpSpreadsheet\Helper\Sample $helper */

// Create new Spreadsheet object
$helper->log('Create new Spreadsheet object');
$spreadsheet = new Spreadsheet();

// Add some data, we will use some formulas here
$helper->log('Add some data and formulas');
$spreadsheet->getActiveSheet()->setCellValue('A1', '=B1')
    ->setCellValue('A2', '=B2+1')
    ->setCellValue('B1', '=A1+1')
    ->setCellValue('B2', '=A2');

Calculation::getInstance($spreadsheet)->cyclicFormulaCount = 15;

// Calculated data
$helper->log('Calculated data');
for ($row = 1; $row <= 2; ++$row) {
    for ($col = 'A'; $col != 'C'; ++$col) {
        $formula = $spreadsheet->getActiveSheet()->getCell($col . $row)->getValue();
        if (
            is_string($formula)
                && ($formula[0] == '=')
        ) {
            $helper->log('Value of ' . $col . $row . ' [' . $formula . ']: ' . $spreadsheet->getActiveSheet()->getCell($col . $row)->getCalculatedValueString());
        }
    }
}

// Save
$helper->write($spreadsheet, __FILE__);
