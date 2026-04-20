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

// ── Table Items ──────────────────────────────────────────────────────────────
$startY = 93.8; // Increased from 92.8 to prevent touching the top line
$rowHeight = 6.45;
$maxRows = 24;   // Max rows that fit on one page template

foreach ($items as $index => $item) {
    if ($index >= $maxRows)
        break; // Limit to one page for now

    $currY = $startY + ($index * $rowHeight);
    
    // --- AUTO RESIZE LOGIC FOR ITEM NAME ---
    $itemName = $item['item_name'];
    $fontSize = 9;
    $colWidth = 38; // Width for Item Name column (approx from X=13 to X=51)
    $pdf->SetFont('helvetica', '', $fontSize);
    
    // Reduce font size if text is too wide
    while ($pdf->GetStringWidth($itemName) > ($colWidth - 2) && $fontSize > 6) {
        $fontSize -= 0.5;
        $pdf->SetFont('helvetica', '', $fontSize);
    }

    // ITEM NAME (Centered now)
    $pdf->SetXY(13, $currY);
    $pdf->Cell($colWidth, $rowHeight, $itemName, 0, 0, 'C');

    // Reset font for quantities
    $pdf->SetFont('helvetica', '', 9);

    // REQUEST NUMBER (Using requested_quantity)
    $pdf->SetXY(51, $currY);
    $pdf->Cell(35, $rowHeight, $item['requested_quantity'], 0, 0, 'C');

    // REQUEST ISSUED (Using approved_quantity)
    $pdf->SetXY(86, $currY);
    $pdf->Cell(35, $rowHeight, $item['approved_quantity'], 0, 0, 'C');

    // RETURNED ITEM (Leave blank or system can't track yet)
    $pdf->SetXY(121, $currY);
    $pdf->Cell(32, $rowHeight, '', 0, 0, 'C');

    // REMARKS (Optional, showing unit for now or leave blank)
    $pdf->SetXY(153, $currY);
    $pdf->Cell(39, $rowHeight, '', 0, 0, 'C');
}

// ── Signature/Borrower ───────────────────────────────────────────────────────
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(140, 245);
$pdf->Cell(52, 5, strtoupper($r['user_name']), 0, 0, 'C');

// ══════════════════════════════════════════════════════════════════════════════
//  Output
// ══════════════════════════════════════════════════════════════════════════════
$filename = 'LaboratoryRequisition_' . $r['reservation_no'] . '.pdf';
$pdf->Output($filename, 'I');
