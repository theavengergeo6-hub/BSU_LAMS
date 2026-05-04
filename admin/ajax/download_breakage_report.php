<?php
require('../../config.php');
require('../../inc/auth.php');
adminLogin();

if (isset($_GET['filename'])) {
    $filename = basename($_GET['filename']);
    $file = __DIR__ . '/../../documents/breakage_reports/' . $filename;
    
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
echo "File not found.";
