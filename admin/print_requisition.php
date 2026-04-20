<?php
/**
 * admin/print_requisition.php
 * Generates a TCPDF PDF of the Laboratory Requisition Form using a JPEG template.
 * Called with ?id=<reservation_id>
 */
require_once('../config.php');
require_once('../inc/auth.php');
adminLogin();

$id = (int) ($_GET['id'] ?? 0);
if (!$id)
    die('Invalid requisition ID.');

// ── Fetch reservation ────────────────────────────────────────────────────────
$res = mysqli_query($con, "SELECT * FROM lab_reservations WHERE id = $id");
$r = mysqli_fetch_assoc($res);
if (!$r)
    die('Requisition not found.');

// ── Fetch items ──────────────────────────────────────────────────────────────
$items_q = mysqli_query($con, "
    SELECT  ri.requested_quantity,
            ri.approved_quantity,
            i.item_name,
            i.unit
    FROM    lab_reservation_items ri
    JOIN    lab_items             i  ON ri.item_id = i.id
    WHERE   ri.reservation_id = $id
    ORDER BY i.item_name
");
$items = [];
while ($row = mysqli_fetch_assoc($items_q))
    $items[] = $row;

// ── Autoload TCPDF ──────────────────────────────────────────────────────────
require_once('../vendor/autoload.php');

// ── Create PDF ───────────────────────────────────────────────────────────────
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('KLRS');
$pdf->SetAuthor('BSU Kitchen Laboratory');
$pdf->SetTitle('Laboratory Requisition Form — #' . $r['reservation_no']);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(0, 0, 0); // No margins for template
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();

// ── Set Template Background ──────────────────────────────────────────────────
$img_file = '../assets/images/lab_requisition_form.jpg';
if (file_exists($img_file)) {
    // Fill the whole page with the image
    $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
} else {
    die('Template image not found at ' . $img_file);
}

// ── Set Font for Data ────────────────────────────────────────────────────────
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0, 0, 0);

// ── Overlay Requisition Data ─────────────────────────────────────────────────
// Coordinates are estimated based on the form layout. Adjust as needed.

// Subject
$pdf->SetXY(35, 63.5);
$pdf->Cell(100, 5, strtoupper($r['subject']), 0, 0, 'L');

// Course & Section
$pdf->SetXY(140, 63.5);
$pdf->Cell(60, 5, strtoupper($r['course_section']), 0, 0, 'L');

// Station
$pdf->SetXY(35, 71.5);
$pdf->Cell(40, 5, $r['station'], 0, 0, 'L');

// Batch
$pdf->SetXY(86, 71.5);
$pdf->Cell(30, 5, $r['batch'], 0, 0, 'L');

// Time
$pdf->SetXY(124, 71.5); // Shifted slightly left to align with "Time:"
$pdf->Cell(35, 5, $r['reservation_time'], 0, 0, 'L');

// Date
$pdf->SetXY(164, 71.5);
$pdf->Cell(40, 5, date('M d, Y', strtotime($r['reservation_date'])), 0, 0, 'L');

// ── Layout Control (Edit these to fine-tune) ──────────────────────────────────
$debug = false;       // Set to true to draw red boxes around all fields
$rowHeight = 5.30;    // YOUR NEW GAP: 110.7 - 105.4 = 5.3
$startY = 94.8;       // YOUR NEW START: 105.4 - (2 * 5.3) = 94.8
$maxRows = 26;        // Increased to fill all 26 rows of the form

// Signature parameters
$sigX = 134;          // Horizontal position for "Request By" name
$sigY = 242.5;        // Vertical position for "Request By" name
$sigW = 55;           // Width of the signature box area
$sigH = 6;            // Height of the signature box area

// ── Column Configuration (Adjust X and Width here) ───────────────────────────
$colItem = ['x' => 13, 'w' => 38];
$colQty = ['x' => 51, 'w' => 35];
$colAppr = ['x' => 86, 'w' => 35];
$colRet = ['x' => 121, 'w' => 32];
$colRem = ['x' => 153, 'w' => 39];

// ── Row Y-Coordinates ────────────────────────────────────────────────────────
// This loop now automatically applies your 5.3 gap to every single row!
$rowY = [];
for ($i = 0; $i < $maxRows; $i++) {
    $rowY[$i] = $startY + ($i * $rowHeight);
}

// ── Table Items ──────────────────────────────────────────────────────────────
foreach ($items as $index => $item) {
    if ($index >= $maxRows)
        break;

    $currY = $rowY[$index];

    // --- ITEM NAME COLUMN ---
    $itemName = $item['item_name'];
    $fontSize = 9;
    $pdf->SetFont('helvetica', '', $fontSize);

    while ($pdf->GetStringWidth($itemName) > ($colItem['w'] - 2.5) && $fontSize > 5) {
        $fontSize -= 0.5;
        $pdf->SetFont('helvetica', '', $fontSize);
    }

    if ($debug) {
        $pdf->SetDrawColor(255, 0, 0);
        $pdf->Rect($colItem['x'], $currY, $colItem['w'], $rowHeight);
    }
    $pdf->SetXY($colItem['x'], $currY);
    $pdf->Cell($colItem['w'], $rowHeight, $itemName, 0, 0, 'C');

    // --- OTHER COLUMNS ---
    $pdf->SetFont('helvetica', '', 9);

    // REQUEST NUMBER
    if ($debug)
        $pdf->Rect($colQty['x'], $currY, $colQty['w'], $rowHeight);
    $pdf->SetXY($colQty['x'], $currY);
    $pdf->Cell($colQty['w'], $rowHeight, $item['requested_quantity'], 0, 0, 'C');

    // REQUEST ISSUED
    if ($debug)
        $pdf->Rect($colAppr['x'], $currY, $colAppr['w'], $rowHeight);
    $pdf->SetXY($colAppr['x'], $currY);
    $pdf->Cell($colAppr['w'], $rowHeight, $item['approved_quantity'], 0, 0, 'C');

    // RETURNED ITEM
    if ($debug)
        $pdf->Rect($colRet['x'], $currY, $colRet['w'], $rowHeight);
    $pdf->SetXY($colRet['x'], $currY);
    $pdf->Cell($colRet['w'], $rowHeight, '', 0, 0, 'C');

    // REMARKS
    if ($debug)
        $pdf->Rect($colRem['x'], $currY, $colRem['w'], $rowHeight);
    $pdf->SetXY($colRem['x'], $currY);
    $pdf->Cell($colRem['w'], $rowHeight, '', 0, 0, 'C');
}

// ── Signature/Borrower ───────────────────────────────────────────────────────
$pdf->SetFont('helvetica', 'B', 10);
if ($debug) {
    $pdf->SetDrawColor(255, 0, 0);
    $pdf->Rect($sigX, $sigY, $sigW, $sigH);
}
$pdf->SetXY($sigX, $sigY);
// Auto-resize for long signature names too!
$sigFontSize = 10;
while ($pdf->GetStringWidth(strtoupper($r['user_name'])) > ($sigW - 1) && $sigFontSize > 7) {
    $sigFontSize -= 0.5;
    $pdf->SetFont('helvetica', 'B', $sigFontSize);
}
$pdf->Cell($sigW, $sigH, strtoupper($r['user_name']), 0, 0, 'C');

// ══════════════════════════════════════════════════════════════════════════════
//  Output
// ══════════════════════════════════════════════════════════════════════════════
$filename = 'LaboratoryRequisition_' . $r['reservation_no'] . '.pdf';
$pdf->Output($filename, 'I');
