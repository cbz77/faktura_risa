<?php
include 'PdfMain.php';
include 'Faktura.php';
include 'data_pro_fakturu.php';

# tvorim PDF
$pdf = new PdfMain();

Faktura::create($pdf, $data_pro_fakturu);

$pdf->Output(__DIR__.'/muj_pdf.pdf', "F");


function textUtf8Win1250($t)
{
    if ( empty($t) ) return '';

    $text = str_replace("\t", "   ", $t); // nahrazení tabelátoru mezerami
    return empty($text) ? '' : iconv("UTF-8", "Windows-1250//TRANSLIT", $text);
}

function getTrans(int $id): string {
    return "PREKLAD K ID " . $id;
}
?>