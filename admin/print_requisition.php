<?php
/**
 * BSU Kitchen Laboratory Requisition Form PDF Generator
 * 
 * Path: admin/print_requisition.php
 * Usage: Triggered via GET request with parameter `id` (requisition primary key ID).
 * 
 * Description:
 * Generates a formal printed requisition sheet by loading a pre-designed JPEG template of the
 * BSU Laboratory Requisition Form, and overlays dynamic database record values (student details,
 * date/times, and borrowed items/quantities) at exact pixel coordinates.
 * Uses the TCPDF library for high-accuracy document construction and typography scaling.
 */

require_once('../config.php');
require_once('../inc/auth.php');

// Restrict access to authenticated laboratory administrators/custodians
adminLogin();

// Safely parse requisition reference identifier
$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    die('Invalid requisition ID.');
}

// ── Query Requisition Metadata ───────────────────────────────────────────────
$res = mysqli_query($con, "SELECT * FROM lab_reservations WHERE id = $id");
$r = mysqli_fetch_assoc($res);
if (!$r) {
    die('Requisition not found.');
}

// ── Query Borrowed Inventory List ────────────────────────────────────────────
// Joins reservation items with master catalog to display the name and physical units.
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
while ($row = mysqli_fetch_assoc($items_q)) {
    $items[] = $row;
}

// Load Composer autoload file containing TCPDF package definitions
require_once('../vendor/autoload.php');

// ── Instantiate and Configure TCPDF Document ──────────────────────────────────
// Setup page format to standard Portrait A4 with millimeter units.
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('KTERS');
$pdf->SetAuthor('BSU Kitchen Laboratory');
$pdf->SetTitle('Laboratory Requisition Form — #' . $r['reservation_no']);

// Suppress default print headers and footers to permit edge-to-edge template printing
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(0, 0, 0); // Remove all margin padding blocks
$pdf->SetAutoPageBreak(false); // Disallow automatic page-breaking to maintain coordinate alignment

$pdf->AddPage();

// ── Embed JPEG Template Background Sheet ──────────────────────────────────────
$img_file = '../assets/images/lab_requisition_form.jpg';
if (file_exists($img_file)) {
    // Render image to occupy the full 210mm x 297mm bounds of A4 paper
    $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
} else {
    die('Template image not found at ' . $img_file);
}

// Set standard font typography parameters for dynamic data layers
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0, 0, 0); // Black ink color

// ── Draw Requisition Form Header Fields ──────────────────────────────────────
// Coordinates represent measured offsets in millimeters from top-left margins.

// Subject Code / Activity Name
$pdf->SetXY(35, 63.5);
$pdf->Cell(100, 5, strtoupper($r['subject']), 0, 0, 'L');

// Course and Section
$pdf->SetXY(140, 63.5);
$pdf->Cell(60, 5, strtoupper($r['course_section']), 0, 0, 'L');

// Station Setup Details
$pdf->SetXY(35, 71.5);
$pdf->Cell(40, 5, $r['station'], 0, 0, 'L');

// Student Batch Number
$pdf->SetXY(86, 71.5);
$pdf->Cell(30, 5, $r['batch'], 0, 0, 'L');

// Session Start Time
$pdf->SetXY(124, 71.5);
$pdf->Cell(35, 5, $r['reservation_time'], 0, 0, 'L');

// Booking Date Formatted nicely
$pdf->SetXY(164, 71.5);
$pdf->Cell(40, 5, date('M d, Y', strtotime($r['reservation_date'])), 0, 0, 'L');

// ── Table Grid Positioning System ───────────────────────────────────────────
$debug = false;       // If set to true, renders red border boxes to visually test cell margins
$rowHeight = 5.30;    // Vertical spacing offset representing the height of single lines on the JPEG form
$startY = 94.8;       // Starting Y axis offset where the item grid rows commence
$maxRows = 26;        // Maximum number of items the template page grid supports (26 table rows)

// Horizontal coordinate offsets (X-axis) and Width boundaries for each column
$colItem = ['x' => 13, 'w' => 38];   // Item Name Column
$colQty  = ['x' => 51, 'w' => 35];   // Requested Quantity Column
$colAppr = ['x' => 86, 'w' => 35];   // Approved Quantity Column
$colRet  = ['x' => 121, 'w' => 32];  // Returned Quantity Column (Left blank for manual fill during check-in)
$colRem  = ['x' => 153, 'w' => 39];  // Remarks Column (Left blank for manual custodian notes)

// Generate row Y-coordinates sequentially using offset formula
$rowY = [];
for ($i = 0; $i < $maxRows; $i++) {
    $rowY[$i] = $startY + ($i * $rowHeight);
}

// ── Populate Table Data Row by Row ───────────────────────────────────────────
foreach ($items as $index => $item) {
    if ($index >= $maxRows) {
        break; // Guard check to prevent drawing beyond form boundary constraints
    }

    $currY = $rowY[$index];

    // --- ITEM NAME COLUMN (WITH AUTO-TYPOGRAPHY SCALING) ---
    $itemName = $item['item_name'];
    $fontSize = 9;
    $pdf->SetFont('helvetica', '', $fontSize);

    // Iteratively decrease font size for extra long item names to fit perfectly within the column borders
    while ($pdf->GetStringWidth($itemName) > ($colItem['w'] - 2.5) && $fontSize > 5) {
        $fontSize -= 0.5;
        $pdf->SetFont('helvetica', '', $fontSize);
    }

    // Render debug boxes if enabled
    if ($debug) {
        $pdf->SetDrawColor(255, 0, 0);
        $pdf->Rect($colItem['x'], $currY, $colItem['w'], $rowHeight);
    }
    $pdf->SetXY($colItem['x'], $currY);
    $pdf->Cell($colItem['w'], $rowHeight, $itemName, 0, 0, 'C');

    // --- QUANTITY AND METADATA COLUMNS ---
    $pdf->SetFont('helvetica', '', 9);

    // REQUESTED QUANTITY
    if ($debug) {
        $pdf->Rect($colQty['x'], $currY, $colQty['w'], $rowHeight);
    }
    $pdf->SetXY($colQty['x'], $currY);
    $pdf->Cell($colQty['w'], $rowHeight, $item['requested_quantity'], 0, 0, 'C');

    // APPROVED QUANTITY
    if ($debug) {
        $pdf->Rect($colAppr['x'], $currY, $colAppr['w'], $rowHeight);
    }
    $pdf->SetXY($colAppr['x'], $currY);
    $pdf->Cell($colAppr['w'], $rowHeight, $item['approved_quantity'], 0, 0, 'C');

    // RETURNED ITEM COLUMN (Drawn as empty box for paper checklist fill-in)
    if ($debug) {
        $pdf->Rect($colRet['x'], $currY, $colRet['w'], $rowHeight);
    }
    $pdf->SetXY($colRet['x'], $currY);
    $pdf->Cell($colRet['w'], $rowHeight, '', 0, 0, 'C');

    // REMARKS COLUMN (Drawn as empty box for paper checklist fill-in)
    if ($debug) {
        $pdf->Rect($colRem['x'], $currY, $colRem['w'], $rowHeight);
    }
    $pdf->SetXY($colRem['x'], $currY);
    $pdf->Cell($colRem['w'], $rowHeight, '', 0, 0, 'C');
}

// ── Draw Borrower Signature Overlay Block ─────────────────────────────────────
$sigX = 134;          // Signature X-axis start millimeter coordinate
$sigY = 242.5;        // Signature Y-axis start millimeter coordinate
$sigW = 55;           // Signature cell width boundary block
$sigH = 6;            // Signature cell height boundary block

$pdf->SetFont('helvetica', 'B', 10);
if ($debug) {
    $pdf->SetDrawColor(255, 0, 0);
    $pdf->Rect($sigX, $sigY, $sigW, $sigH);
}
$pdf->SetXY($sigX, $sigY);

// Automatically scale down signature text font size if name is extremely long
$sigFontSize = 10;
while ($pdf->GetStringWidth(strtoupper($r['user_name'])) > ($sigW - 1) && $sigFontSize > 7) {
    $sigFontSize -= 0.5;
    $pdf->SetFont('helvetica', 'B', $sigFontSize);
}
// Render the name block centered over the signature line
$pdf->Cell($sigW, $sigH, strtoupper($r['user_name']), 0, 0, 'C');

// ── Output PDF Stream ────────────────────────────────────────────────────────
$filename = 'LaboratoryRequisition_' . $r['reservation_no'] . '.pdf';
$pdf->Output($filename, 'I'); // Stream inline to browser view
?>
