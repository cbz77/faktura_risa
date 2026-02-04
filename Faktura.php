<?php

class Faktura
{

    private const POLOZEK_NA_STRANKU = 10;

    public static function create(FPDF $pdf, array $data): void
    {
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        self::frame($pdf);
        self::header($pdf,$data);
        self::infoBoxes($pdf,$data);
        self::renderPolozky($pdf,$data);
        self::bottom($pdf,$data);
    }

    /* ========================================================== */
    private static function frame(FPDF $pdf)
    {
        $pdf->SetLineWidth(0.6);
        $pdf->Rect(5,5,200,287);
    }

    /* ========================================================== */
    private static function header(FPDF $pdf,$data)
    {
        $pdf->SetFont('font','B',16);

        $pdf->SetXY(7,8);
        $pdf->Cell(100,8,'DODACÍ LIST',0,0);

        $pdf->SetFont('font','',14);
        $pdf->Cell(90,8,$data['cislo_dokladu'],0,1,'R');

        // horní box
        $pdf->Rect(5,18,200,75);
    }

    /* ========================================================== */
    private static function infoBoxes(FPDF $pdf,$data)
    {
        $dod = $data['dodavatel'];
        $odb = $data['odberatel'];

        // rozdìlení boxu na 2 sloupce
        $pdf->Line(105,18,105,93);

        /* ===== DODAVATEL ===== */
        $pdf->SetXY(7,20);

        $pdf->SetFont('font','B',12);
        $pdf->Cell(95,6,$dod['jmeno'],0,1);

        $pdf->SetFont('font','',10);
        $pdf->Cell(95,5,$dod['ulice'],0,1);
        $pdf->Cell(95,5,$dod['psc'].' '.$dod['mesto'],0,1);
        $pdf->Cell(95,5,$dod['stat'],0,1);

        $pdf->Ln(2);
        $pdf->Cell(95,5,'IÈO: '.$dod['ico'],0,1);
        $pdf->Cell(95,5,'DIÈ: '.$dod['dic'],0,1);

        if (!empty($data['banka']))
        {
            $b = $data['banka'];

            $pdf->Ln(2);
            $pdf->Cell(95,5,'ÚÈET: '.$b['ucet'],0,1);
            $pdf->Cell(95,5,'IBAN: '.$b['iban'],0,1);
            $pdf->Cell(95,5,'SWIFT: '.$b['swift'],0,1);
        }

        /* ===== ODBÌRATEL ===== */
        $pdf->SetXY(107,20);

        $pdf->SetFont('font','B',10);
        $pdf->Cell(90,6,'Dodací list pro:',0,1);

        $pdf->SetFont('font','',11);

        $pdf->SetX(107);
        $pdf->Cell(90,6,$odb['jmeno'],0,1);

        $pdf->SetX(107);
        $pdf->Cell(90,6,$odb['ulice'],0,1);

        $pdf->SetX(107);
        $pdf->Cell(90,6,$odb['psc'].' '.$odb['mesto'],0,1);

        $pdf->SetX(107);
        $pdf->Cell(90,6,$odb['stat'],0,1);

        // dodací adresa
        if (!empty($data['dodaci_adresa']))
        {
            $da = $data['dodaci_adresa'];

            $pdf->Ln(4);
            $pdf->SetX(107);
            $pdf->SetFont('font','B',10);
            $pdf->Cell(90,6,'Dodací adresa:',0,1);

            $pdf->SetFont('font','',11);

            $pdf->SetX(107);
            $pdf->Cell(90,6,$da['jmeno'],0,1);

            $pdf->SetX(107);
            $pdf->Cell(90,6,$da['ulice'],0,1);

            $pdf->SetX(107);
            $pdf->Cell(90,6,$da['psc'].' '.$da['mesto'],0,1);
        }

        /* ===== øádek dat ===== */
        $pdf->Rect(5,93,200,15);

        $pdf->Line(68,93,68,108);
        $pdf->Line(131,93,131,108);

        $pdf->SetXY(7,95);
        $pdf->SetFont('font','',10);

        $pdf->Cell(61,5,'Datum vystavení',0,0);
        $pdf->Cell(63,5,'Objednávka èíslo',0,0);
        $pdf->Cell(63,5,'Faktura èíslo',0,1);

        $pdf->SetX(7);

        $pdf->SetFont('font','B',10);
        $pdf->Cell(61,7,$data['datum_vystaveni'],0,0);
        $pdf->Cell(63,7,$data['objednavka_cislo'] ?? '',0,0);
        $pdf->Cell(63,7,$data['faktura_cislo'] ?? '',0,1);
    }

    private static function renderPolozkyHeader(FPDF $pdf): void
    {
        $pdf->SetFont('font','B',9);

        $pdf->Cell(95,7,'Popis položky',1);
        $pdf->Cell(15,7,'Množství',1,0,'R');
        $pdf->Cell(10,7,'MJ',1,0,'C');
        $pdf->Cell(25,7,'Cena za MJ',1,0,'R');
        $pdf->Cell(30,7,'Celkem bez DPH',1,0,'R');
        $pdf->Cell(10,7,'DPH',1,0,'R');
        $pdf->Cell(30,7,'Celkem s DPH',1,1,'R');

        $pdf->SetFont('font','',9);
    }

    /* ========================================================== */
   private static function renderPolozky(FPDF $pdf, array $data): void
    {
        $pocitadlo = 0;

        self::renderPolozkyHeader($pdf);

        foreach ($data['polozky'] as $polozka) {

            if ($pocitadlo > 0 && $pocitadlo % self::POLOZEK_NA_STRANKU === 0) {
                $pdf->AddPage();
                self::renderPolozkyHeader($pdf);
            }

            $yStart = $pdf->GetY();

            // Popis – MultiCell
            $pdf->MultiCell(95,6,$polozka['popis'],1);

            $height = $pdf->GetY() - $yStart;

            $pdf->SetXY(110,$yStart);

            $pdf->Cell(15,$height,$polozka['mnozstvi'],1,0,'R');
            $pdf->Cell(10,$height,$polozka['mj'],1,0,'C');
            $pdf->Cell(25,$height,number_format($polozka['cena_za_mj'],2,',',' '),1,0,'R');
            $pdf->Cell(30,$height,number_format($polozka['bez_dph'],2,',',' '),1,0,'R');
            $pdf->Cell(10,$height,$polozka['dph_sazba'].'%',1,0,'R');
            $pdf->Cell(30,$height,number_format($polozka['s_dph'],2,',',' '),1,1,'R');

            $pocitadlo++;
        }

        /*
        ========================
        ØÁDEK CELKEM
        ========================
        */

        $s = $data['soucty'];

        $pdf->SetFont('font','B',9);

        // slouèené první 4 sloupce
        $pdf->Cell(95+15+10+25,7,'Celkem:',1,0,'R');

        // souèty
        $pdf->Cell(30,7,number_format($s['bez_dph'],2,',',' '),1,0,'R');
        $pdf->Cell(10,7,number_format($s['dph'],2,',',' '),1,0,'R');
        $pdf->Cell(30,7,number_format($s['s_dph'],2,',',' '),1,1,'R');

        $pdf->SetFont('font','',9);

        /*
        ========================
        POZNÁMKA POD TABULKOU
        ========================
        */

        if (!empty($data['poznamka'])) {

            $pdf->Ln(4);

            $pdf->MultiCell(
                0,
                5,
                $data['poznamka']
            );
        }
    }

    /* ========================================================== */
    private static function bottom(FPDF $pdf,$data)
    {
        $s = $data['soucty'];

        $y = 225;

        $pageX = 5;
        $pageWidth = 200;
        $colWidth = $pageWidth / 3;

        $col1 = $pageX;
        $col2 = $pageX + $colWidth;
        $col3 = $pageX + ($colWidth * 2);

        // ===== hlavní rám =====
        $pdf->Rect($pageX,$y,$pageWidth,67);

        // ===== vertikální dìlení =====
        $pdf->Line($col2,$y,$col2,292);
        $pdf->Line($col3,$y,$col3,292);


        /*
        =====================
        LEVÝ SLOUPEC
        =====================
        */

        $pdf->SetFont('font','',10);
        $pdf->SetXY($col1 + 3,$y + 5);
        $pdf->Cell($colWidth - 6,6,'Vyhotovil:',0,1);

        if(!empty($data['razitko']))
        {
            $pdf->Image(
                $data['razitko'],
                $col1 + 10,
                $y + 15,
                $colWidth - 20
            );
        }


        /*
        =====================
        STØED
        =====================
        */

        $pdf->SetXY($col2 + 3,$y + 5);
        $pdf->Cell($colWidth - 6,6,'Prevzal:',0,1);


        /*
        =====================
        PRAVÝ SLOUPEC – SOUÈTY
        =====================
        */

        $x = $col3 + 3;
        $yy = $y + 5;

        $pdf->SetFont('font','',10);

        // bez DPH
        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth - 40,7,'Celková èástka bez DPH:',0,0);
        $pdf->Cell(37,7,number_format($s['bez_dph'],2,',',' ').' Kè',0,1,'R');

        // DPH
        $yy += 7;
        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth - 40,7,'DPH:',0,0);
        $pdf->Cell(37,7,number_format($s['dph'],2,',',' ').' Kè',0,1,'R');

        // s DPH
        $yy += 7;
        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth - 40,7,'Celková èástka s DPH:',0,0);
        $pdf->Cell(37,7,number_format($s['s_dph'],2,',',' ').' Kè',0,1,'R');


        // CELKEM
        $yy += 12;

        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth - 6,6,'Celkem:',0,1);

        $yy += 6;

        $pdf->SetFont('font','B',16);
        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth - 6,9,
            number_format($s['s_dph'],2,',',' ').' Kè',
            0,1,'R'
        );

        $yy += 10;

        $pdf->SetFont('font','',9);
        $pdf->SetXY($x,$yy);
        $pdf->MultiCell(
            $colWidth - 6,
            5,
            $s['slovy'],
            0,
            'R'
        );
    }



}
