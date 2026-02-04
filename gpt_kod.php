<?php

require('fpdf.php');

class PDF extends FPDF
{

    function Header()
    {
        // Rámeèek stránky
        $this->SetLineWidth(0.5);
        $this->Rect(5,5,200,287);

        // Titulek
        $this->SetFont('Arial','B',16);
        $this->SetXY(7,10);
        $this->Cell(0,10,'DODACÍ LIST',0,0,'L');

        // Èíslo DL
        $this->SetFont('Arial','',14);
        $this->Cell(0,10,'DL-2023-0001',0,1,'R');

        $this->Ln(5);
    }
}


$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);


// =====================================================
// LEVÝ BLOK - DODAVATEL
// =====================================================

$startY = $pdf->GetY();

$pdf->SetXY(7,$startY);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(95,6,'Hammer Masters s.r.o.',0,1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(95,5,'U Vodárny 1081/2',0,1);
$pdf->Cell(95,5,'530 09 Pardubice',0,1);
$pdf->Cell(95,5,'Èeská republika',0,1);

$pdf->Ln(3);

$pdf->Cell(95,5,'IÈO: 83125649',0,1);
$pdf->Cell(95,5,'DIÈ: CZ83125649',0,1);

$pdf->Ln(3);

$pdf->Cell(95,5,'Telefon: +420 466 220 0001',0,1);
$pdf->Cell(95,5,'Email: info@hammermasters.cz',0,1);


// =====================================================
// PRAVÝ BLOK - ODBÌRATEL
// =====================================================

$pdf->SetXY(107,$startY);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,6,'Dodací list pro:',0,1);

$pdf->SetFont('Arial','',11);
$pdf->SetX(107);
$pdf->Cell(90,6,'Bytový komplex Sluneèná zahrada, s.r.o.',0,1);
$pdf->SetX(107);
$pdf->Cell(90,6,'Kvìtnová 78/15',0,1);
$pdf->SetX(107);
$pdf->Cell(90,6,'140 00 Praha 4',0,1);

$pdf->Ln(5);

$pdf->SetX(107);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(90,6,'Dodací adresa:',0,1);

$pdf->SetFont('Arial','',11);
$pdf->SetX(107);
$pdf->Cell(90,6,'U Sluneèního vršku 837',0,1);
$pdf->SetX(107);
$pdf->Cell(90,6,'140 00 Praha 4',0,1);


// =====================================================
// DATUM / OBJEDNÁVKA / FAKTURA
// =====================================================

$pdf->Ln(10);

$pdf->Cell(63,8,'Datum vystavení',1);
$pdf->Cell(63,8,'Objednávka èíslo',1);
$pdf->Cell(63,8,'Faktura èíslo',1);
$pdf->Ln();

$pdf->Cell(63,8,'28.4.2023',1);
$pdf->Cell(63,8,'',1);
$pdf->Cell(63,8,'',1);
$pdf->Ln(12);


// =====================================================
// TABULKA POLOŽEK
// =====================================================

function tableHeader($pdf)
{
    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(70,8,'Popis položky',1);
    $pdf->Cell(20,8,'Množství',1);
    $pdf->Cell(15,8,'MJ',1);
    $pdf->Cell(25,8,'Cena za MJ',1);
    $pdf->Cell(30,8,'Celkem bez DPH',1);
    $pdf->Cell(15,8,'DPH',1);
    $pdf->Cell(25,8,'Celkem s DPH',1);

    $pdf->Ln();
}

tableHeader($pdf);

$pdf->SetFont('Arial','',9);

$data = [
    ['Cihla broušená Porotherm',250,'ks','82,64','20 661,16','21%','25 000,00'],
    ['Hydroizolaèní fólie',10,'ks','6 694,21','66 942,15','21%','81 000,00'],
    ['Hliníkové žebøíky',3,'ks','2 148,76','6 446,28','21%','7 800,00'],
];

foreach($data as $row)
{
    $pdf->Cell(70,8,$row[0],1);
    $pdf->Cell(20,8,$row[1],1,0,'C');
    $pdf->Cell(15,8,$row[2],1,0,'C');
    $pdf->Cell(25,8,$row[3],1,0,'R');
    $pdf->Cell(30,8,$row[4],1,0,'R');
    $pdf->Cell(15,8,$row[5],1,0,'C');
    $pdf->Cell(25,8,$row[6],1,0,'R');
    $pdf->Ln();
}


// =====================================================
// SOUHRN CEN
// =====================================================

$pdf->Ln(10);

$pdf->SetX(110);

$pdf->Cell(50,8,'Celková èástka bez DPH:',1);
$pdf->Cell(40,8,'128 966,94 Kè',1,1,'R');

$pdf->SetX(110);
$pdf->Cell(50,8,'DPH:',1);
$pdf->Cell(40,8,'27 083,06 Kè',1,1,'R');

$pdf->SetX(110);
$pdf->Cell(50,8,'Celková èástka s DPH:',1);
$pdf->Cell(40,8,'156 050,00 Kè',1,1,'R');

$pdf->SetFont('Arial','B',14);

$pdf->SetX(110);
$pdf->Cell(50,12,'Celkem:',1);
$pdf->Cell(40,12,'156 050,00 Kè',1,1,'R');


// =====================================================

$pdf->Output();
