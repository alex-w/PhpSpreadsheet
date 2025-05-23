<?php

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Helpers as DateHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

require __DIR__ . '/../Header.php';
/** @var PhpOffice\PhpSpreadsheet\Helper\Sample $helper */
$helper->log('Returns the number of days from the settlement date to the next coupon date.');

// Create new PhpSpreadsheet object
$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

// Add some data
$arguments = [
    ['Settlement Date', DateHelper::getDateValue('01-Jan-2011')],
    ['Maturity Date', DateHelper::getDateValue('25-Oct-2012')],
    ['Frequency', 4],
];

// Some basic formatting for the data
$worksheet->fromArray($arguments, null, 'A1');
$worksheet->getStyle('B1:B2')->getNumberFormat()->setFormatCode('dd-mmm-yyyy');

// Now the formula
$worksheet->setCellValue('B6', '=COUPDAYSNC(B1, B2, B3)');

$helper->log($worksheet->getCell('B6')->getValue());
$helper->log('COUPDAYSNC() Result is ' . $worksheet->getCell('B6')->getFormattedValue());
