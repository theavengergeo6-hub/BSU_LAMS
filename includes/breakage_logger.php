<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function get_current_month_file() {
    $month = date('M_Y');
    return __DIR__ . "/../documents/breakage_reports/TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT_{$month}.xlsx";
}

function create_new_breakage_report() {
    $file = get_current_month_file();
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if (file_exists($file)) {
        return false;
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $month_str = date('F Y');

    // Title
    $sheet->setCellValue('A1', 'TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Month
    $sheet->setCellValue('A3', 'MONTH: ' . $month_str);
    $sheet->getStyle('A3')->getFont()->setBold(true);

    // Headers
    $sheet->setCellValue('A5', 'ITEM NAME');
    $sheet->setCellValue('B5', 'UNIT');
    $sheet->setCellValue('C5', 'QUANTITY');
    $sheet->setCellValue('D5', 'REMARKS');
    $sheet->getStyle('A5:D5')->getFont()->setBold(true);
    $sheet->getStyle('A5:D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(40);

    // Signature lines at bottom (starts at row 6)
    $sheet->setCellValue('A6', 'Prepared by:');
    $sheet->setCellValue('C6', 'Noted by:');

    $sheet->setCellValue('A8', 'Ms. Reah Taganas');
    $sheet->getStyle('A8')->getFont()->setBold(true);
    $sheet->setCellValue('C8', 'Dr. Marithe J. Tiango');
    $sheet->getStyle('C8')->getFont()->setBold(true);

    $sheet->setCellValue('A9', 'Laboratory Custodian');
    $sheet->setCellValue('C9', 'Department Chair, BS');

    $writer = new Xlsx($spreadsheet);
    $writer->save($file);
    return true;
}

function append_to_breakage_report($item_name, $unit, $quantity, $remarks) {
    $file = get_current_month_file();
    if (!file_exists($file)) {
        create_new_breakage_report();
    }

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();

    // Find the signature lines to insert ABOVE them
    $highestRow = $sheet->getHighestRow();
    $insertRow = $highestRow;
    
    // Scan backwards to find 'Prepared by:'
    for ($row = $highestRow; $row >= 1; $row--) {
        if ($sheet->getCell("A{$row}")->getValue() == 'Prepared by:') {
            $insertRow = $row;
            break;
        }
    }

    // Insert new row
    $sheet->insertNewRowBefore($insertRow, 1);
    
    $sheet->setCellValue('A' . $insertRow, $item_name);
    $sheet->setCellValue('B' . $insertRow, $unit);
    $sheet->setCellValue('C' . $insertRow, $quantity);
    $sheet->setCellValue('D' . $insertRow, $remarks);

    $writer = new Xlsx($spreadsheet);
    $writer->save($file);
}

function get_available_months() {
    $dir = __DIR__ . '/../documents/breakage_reports/';
    if (!is_dir($dir)) return [];
    
    $files = scandir($dir);
    $months = [];
    foreach ($files as $file) {
        if (preg_match('/TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT_(.*)\.xlsx/', $file, $matches)) {
            $months[] = [
                'filename' => $file,
                'month_str' => str_replace('_', ' ', $matches[1]), // e.g. "Apr 2026"
                'month_key' => $matches[1] // e.g. "Apr_2026"
            ];
        }
    }
    // Sort by descending
    usort($months, function($a, $b) {
        return strtotime($b['month_str']) - strtotime($a['month_str']);
    });
    return $months;
}

function read_monthly_report($filename) {
    $file = __DIR__ . '/../documents/breakage_reports/' . $filename;
    if (!file_exists($file)) return [];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    
    $data = [];
    // Start reading from row 6
    for ($row = 6; $row <= $highestRow; $row++) {
        $cellA = $sheet->getCell("A{$row}")->getValue();
        if ($cellA == 'Prepared by:') {
            break;
        }
        if (!empty($cellA)) {
            $data[] = [
                'item_name' => $cellA,
                'unit' => $sheet->getCell("B{$row}")->getValue(),
                'quantity' => $sheet->getCell("C{$row}")->getValue(),
                'remarks' => $sheet->getCell("D{$row}")->getValue()
            ];
        }
    }
    return $data;
}
?>
