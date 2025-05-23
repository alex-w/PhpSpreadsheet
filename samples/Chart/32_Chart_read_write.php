<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';
/** @var PhpOffice\PhpSpreadsheet\Helper\Sample $helper */
$inputFileType = 'Xlsx';
$inputFileNames = __DIR__ . '/../templates/32readwrite*[0-9].xlsx';

if ((isset($argc)) && ($argc > 1)) {
    $inputFileNames = [];
    for ($i = 1; $i < $argc; ++$i) {
        $inputFileNames[] = __DIR__ . '/../templates/' . $argv[$i];
    }
} else {
    $inputFileNames = glob($inputFileNames) ?: [];
}
foreach ($inputFileNames as $inputFileName) {
    $inputFileNameShort = basename($inputFileName);

    if (!file_exists($inputFileName)) {
        $helper->log('File ' . $inputFileNameShort . ' does not exist');

        continue;
    }
    $reader = IOFactory::createReader($inputFileType);
    $reader->setIncludeCharts(true);
    $callStartTime = microtime(true);
    $spreadsheet = $reader->load($inputFileName);
    $helper->logRead($inputFileType, $inputFileName, $callStartTime);

    $helper->log('Iterate worksheets looking at the charts');
    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
        $sheetName = $worksheet->getTitle();
        $helper->log('Worksheet: ' . $sheetName);

        $chartNames = $worksheet->getChartNames();
        if (empty($chartNames)) {
            $helper->log('    There are no charts in this worksheet');
        } else {
            natsort($chartNames);
            foreach ($chartNames as $i => $chartName) {
                $chart = $worksheet->getChartByNameOrThrow($chartName);
                if ($chart->getTitle() !== null) {
                    $caption = '"' . $chart->getTitle()->getCaptionText($spreadsheet) . '"';
                } else {
                    $caption = 'Untitled';
                }
                $helper->log('    ' . $chartName . ' - ' . $caption);
                $indentation = str_repeat(' ', strlen($chartName) + 3);
                $groupCount = $chart->getPlotAreaOrThrow()->getPlotGroupCount();
                if ($groupCount == 1) {
                    $chartType = $chart->getPlotAreaOrThrow()->getPlotGroupByIndex(0)->getPlotType();
                    $helper->log($indentation . '    ' . $chartType);
                    $helper->renderChart($chart, __FILE__);
                } else {
                    $chartTypes = [];
                    for ($i = 0; $i < $groupCount; ++$i) {
                        $chartTypes[] = $chart->getPlotAreaOrThrow()->getPlotGroupByIndex($i)->getPlotType();
                    }
                    $chartTypes = array_unique($chartTypes);
                    if (count($chartTypes) == 1) {
                        $chartType = 'Multiple Plot ' . array_pop($chartTypes);
                        $helper->log($indentation . '    ' . $chartType);
                        $helper->renderChart($chart, __FILE__);
                    } elseif (count($chartTypes) == 0) {
                        $helper->log($indentation . '    *** Type not yet implemented');
                    } else {
                        $helper->log($indentation . '    Combination Chart');
                        $helper->renderChart($chart, __FILE__);
                    }
                }
            }
        }
    }

    $outputFileName = $helper->getFilename($inputFileName);
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->setIncludeCharts(true);
    $callStartTime = microtime(true);
    $writer->save($outputFileName);
    $helper->logWrite($writer, $outputFileName, $callStartTime);

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
}
