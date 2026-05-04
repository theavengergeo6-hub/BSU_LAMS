<?php
require('../../config.php');
require('../../inc/auth.php');
require('../../includes/breakage_logger.php');
require_once('../../vendor/autoload.php'); // For TCPDF
adminLogin();

if (!isset($_GET['filename'])) {
    die("Filename required");
}

$filename = basename($_GET['filename']);
$data = read_monthly_report($filename);

$month_str = str_replace('TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT_', '', $filename);
$month_str = str_replace('.xlsx', '', $month_str);
$month_str = str_replace('_', ' ', $month_str);

// Create new PDF document in Landscape orientation
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('System');
$pdf->SetTitle('Breakage Report - ' . $month_str);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

// Set background image if exists (A4 Landscape: 297x210)
$bg_image = '../../assets/images/breakage_report_bg.jpg';
$has_bg = file_exists($bg_image);

if ($has_bg) {
    $bMargin = $pdf->getBreakMargin();
    $auto_page_break = $pdf->getAutoPageBreak();
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->Image($bg_image, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    $pdf->setPageMark();

    // --- MANUAL COORDINATES FOR ALIGNMENT ---

    // 1. Position for the MONTH text
    $pdf->SetXY(52.5, 73.5); // Adjust these numbers to align with your image's "MONTH: _____"
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 10, $month_str, 0, 1, 'L');

    // 2. COLUMN X-COORDINATES (Adjust these to line up with your image columns)
    $col_x_name = 47;    // "NAME" column start
    $col_x_unit = 100;   // "UNIT" column start
    $col_x_qty = 162;    // "QUANTITY" column start
    $col_x_remarks = 235; // "REMARKS" column start

    $current_y = 94; // Starting Y position for the first row
    $row_height = 4.5; // Space between rows (Updated to prevent drifting)

    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $row) {
        // Name Column
        $pdf->SetXY($col_x_name, $current_y);
        $pdf->Cell(85, 8, $row['item_name'], 0, 0, 'L');

        // Unit Column
        $pdf->SetXY($col_x_unit, $current_y);
        $pdf->Cell(35, 8, $row['unit'], 0, 0, 'C');

        // Quantity Column
        $pdf->SetXY($col_x_qty, $current_y);
        $pdf->Cell(35, 8, $row['quantity'], 0, 0, 'C');

        // Remarks Column
        $pdf->SetXY($col_x_remarks, $current_y);
        $pdf->Cell(100, 8, $row['remarks'], 0, 1, 'L');

        $current_y += $row_height;
    }

} else {
    // Standard layout for when no background image is present
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'TOOLS AND EQUIPMENT BREAKAGE MONITORING REPORT', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'MONTH: ' . $month_str, 0, 1, 'L');
    $pdf->Ln(2);

    // Table Header
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(90, 8, 'ITEM NAME', 1, 0, 'C');
    $pdf->Cell(40, 8, 'UNIT', 1, 0, 'C');
    $pdf->Cell(40, 8, 'QUANTITY', 1, 0, 'C');
    $pdf->Cell(100, 8, 'REMARKS', 1, 1, 'C');

    // Table Data
    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $row) {
        $pdf->Cell(90, 8, $row['item_name'], 1, 0, 'L');
        $pdf->Cell(40, 8, $row['unit'], 1, 0, 'C');
        $pdf->Cell(40, 8, $row['quantity'], 1, 0, 'C');
        $pdf->Cell(100, 8, $row['remarks'], 1, 1, 'L');
    }

    $pdf->Ln(15);
    // Signatures
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(100, 6, 'Prepared by:', 0, 0, 'L');
    $pdf->Cell(90, 6, 'Noted by:', 0, 1, 'L');
    $pdf->Ln(10);

    $pdf->Cell(100, 6, 'Ms. Reah Taganas', 0, 0, 'L');
    $pdf->Cell(90, 6, 'Dr. Marithe J. Tiango', 0, 1, 'L');

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(100, 6, 'Laboratory Custodian', 0, 0, 'L');
    $pdf->Cell(90, 6, 'Department Chair, BS', 0, 1, 'L');
}

// Output PDF
$pdf->Output('breakage_report_' . str_replace(' ', '_', $month_str) . '.pdf', 'I');
