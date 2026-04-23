<?php
require_once('../config.php');
require_once('../inc/auth.php');
adminLogin();
require_once('../vendor/autoload.php');

// ── TCPDF setup ──────────────────────────────────────────────────────────────
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'BATANGAS STATE UNIVERSITY', 0, 1, 'C');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'ARASOF-Nasugbu Campus', 0, 1, 'C');
        $this->Cell(0, 5, 'Kitchen Laboratory Asset Management', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(0, 10, 'LABORATORY ASSET DISPOSAL REPORT', 0, 1, 'C', 1);
        $this->Ln(5);
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages().' - Generated on '.date('Y-m-d H:i'), 0, 0, 'C');
    }
}

$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('KLRS');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Disposal Report');
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(15);
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();

// ── Query Data ───────────────────────────────────────────────────────────────
// Part 1: Items eligible for disposal (Aged 3+ years)
$eligible_q = mysqli_query($con, "
    SELECT i.*, c.name as cat_name 
    FROM lab_items i 
    JOIN lab_categories c ON i.category_id = c.id
    WHERE i.acquisition_date <= DATE_SUB(CURDATE(), INTERVAL 3 YEAR)
      AND i.total_quantity > 0
    ORDER BY i.acquisition_date ASC
");

// Part 2: Already disposed items (recorded in logs)
$disposed_q = mysqli_query($con, "
    SELECT l.*, i.item_name, c.name as cat_name, i.acquisition_date
    FROM lab_item_logs l
    JOIN lab_items i ON l.item_id = i.id
    JOIN lab_categories c ON i.category_id = c.id
    WHERE l.is_disposal = 1
    ORDER BY l.created_at DESC
");

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 10, 'I. ITEMS ELIGIBLE FOR DISPOSAL (Aged 3+ Years)', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(60, 8, 'Item Name', 1, 0, 'C', 1);
$pdf->Cell(40, 8, 'Category', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Acq. Date', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Age', 1, 0, 'C', 1);
$pdf->Cell(20, 8, 'Qty', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 9);
if(mysqli_num_rows($eligible_q) == 0) {
    $pdf->Cell(180, 8, 'No items currently eligible for disposal.', 1, 1, 'C');
} else {
    while($row = mysqli_fetch_assoc($eligible_q)) {
        $ad = new DateTime($row['acquisition_date']);
        $now = new DateTime();
        $diff = $now->diff($ad);
        $age = $diff->y . "y " . $diff->m . "m";
        
        $pdf->Cell(60, 8, $row['item_name'], 1, 0, 'L');
        $pdf->Cell(40, 8, $row['cat_name'], 1, 0, 'L');
        $pdf->Cell(30, 8, $row['acquisition_date'], 1, 0, 'C');
        $pdf->Cell(30, 8, $age, 1, 0, 'C');
        $pdf->Cell(20, 8, $row['total_quantity'], 1, 1, 'C');
    }
}

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 10, 'II. DISPOSED ASSETS HISTORY', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(50, 8, 'Item Name', 1, 0, 'C', 1);
$pdf->Cell(25, 8, 'Disposed Date', 1, 0, 'C', 1);
$pdf->Cell(15, 8, 'Qty', 1, 0, 'C', 1);
$pdf->Cell(90, 8, 'Disposal Reason / Remarks', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 9);
if(mysqli_num_rows($disposed_q) == 0) {
    $pdf->Cell(180, 8, 'No disposal records found.', 1, 1, 'C');
} else {
    while($row = mysqli_fetch_assoc($disposed_q)) {
        $pdf->Cell(50, 8, $row['item_name'], 1, 0, 'L');
        $pdf->Cell(25, 8, date('Y-m-d', strtotime($row['created_at'])), 1, 0, 'C');
        $pdf->Cell(15, 8, $row['quantity_change'], 1, 0, 'C');
        $pdf->Cell(90, 8, $row['disposal_reason'] ?: $row['remarks'], 1, 1, 'L');
    }
}

// ── Signatures ───────────────────────────────────────────────────────────────
$pdf->Ln(20);
if ($pdf->GetY() > 250) $pdf->AddPage();

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(90, 5, 'Prepared by:', 0, 0, 'L');
$pdf->Cell(90, 5, 'Approved by:', 0, 1, 'L');
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(90, 5, '__________________________', 0, 0, 'L');
$pdf->Cell(90, 5, '__________________________', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(90, 5, 'Lab Custodian', 0, 0, 'L');
$pdf->Cell(90, 5, 'Department Head', 0, 1, 'L');

$pdf->Output('Disposal_Report_'.date('Ymd').'.pdf', 'I');
?>
