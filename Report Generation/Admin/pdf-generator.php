<?php
// pdf_generator.php
require_once('../../tcpdf/tcpdf.php');

function createPdf($title) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle($title);
    $pdf->SetSubject($title);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font for the title
    $pdf->SetFont('helvetica', 'B', 16);
    
    // Add headline
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    
    return $pdf;
}

?>
