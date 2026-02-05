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
        self::renderPolozky($pdf,$data);
        self::bottom($pdf,$data);
    }

    /* ========================================================== */
    private static function frame(FPDF $pdf)
    {
        $pdf->Image(__DIR__.'/carovy_kod.png', 4, 8, 50);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect(5,15,200,277);
    }

    /* ========================================================== */
    private static function header(FPDF $pdf,$data)
    {
        $pdf->SetFont('font','',16);

        $pdf->SetXY(7,16);
        $pdf->Cell(100,8,'DODACÍ LIST',0,0);

        $pdf->SetFont('font','',14);
        $pdf->Cell(90,8,$data['cislo_dokladu'],0,1,'R');

        // horní box
        $pdf->Rect(5,25,200,88);

        $dod = $data['dodavatel'];
        $odb = $data['odberatel'];

        // rozdìlení boxu na 2 sloupce
        $pdf->Line(105,25,105,113);

        /* ===== DODAVATEL ===== */
        $pdf->SetXY(7,27);

        $pdf->SetFont('font','',16);
        $pdf->Cell(95,7,$dod['jmeno'],0,1);

        $pdf->SetFont('font','',11);
        $pdf->SetX(7);
        $pdf->Cell(95,4,$dod['ulice'],0,1);
        $pdf->SetX(7);
        $pdf->Cell(95,4,$dod['psc'].' '.$dod['mesto'],0,1);
        $pdf->SetX(7);
        $pdf->Cell(95,4,$dod['stat'],0,1);

        $pdf->SetFont('font','',10);
        $pdf->Ln(2);
        $pdf->SetX(7);

        $pdf->MultiCell(65,4,$dod['registrace'],0,1);

        $left   = 7;
        $totalW = 65;
        $labelW = 35;
        $valueW = $totalW - $labelW;

        $pdf->SetFont('font','',11);
        $pdf->SetX($left);
        $pdf->Ln(2);

        // IÈO / DIÈ / plátce
        $pdf->SetX($left);
        $pdf->Cell($labelW,4,'IÈO:',0,0,'L');
        $pdf->Cell($valueW,4,$dod['ico'],0,1,'L');

        $pdf->SetX($left);
        $pdf->Cell($labelW,4,'DIÈ:',0,0,'L');
        $pdf->Cell($valueW,4,$dod['dic'],0,1,'L');

        $pdf->SetX($left);
        $pdf->Cell($labelW,4,$dod['platce_dph'],0,0,'L');
        $pdf->Cell($valueW,4,'',0,1,'L');

        $pdf->Ln(2);

        // kontakt
        $pdf->SetFont('font','',9);

        $pdf->SetX($left);
        $pdf->Cell($labelW,4,'TELEFON:',0,0,'L');
        $pdf->Cell($valueW,4,$dod['telefon'],0,1,'L');

        $pdf->SetX($left);
        $pdf->Cell($labelW,4,'E-MAIL:',0,0,'L');
        $pdf->Cell($valueW,4,$dod['email'],0,1,'L');

        $pdf->SetX($left);
        $pdf->Cell($labelW,4,'WEB:',0,0,'L');
        $pdf->Cell($valueW,4,$dod['web'],0,1,'L');

        //banka

        if (!empty($data['banka'])) {

            $b = $data['banka'];

            $pdf->Ln(2);

            $pdf->SetX($left);
            $pdf->Cell($labelW,4,'ÚÈET:',0,0,'L');
            $pdf->Cell($valueW,4,$b['ucet'],0,1,'L');

            $pdf->SetFont('font','B',9);
            $pdf->SetX($left);
            $pdf->Cell($labelW,4,'BANKA:',0,0,'L');
            $pdf->Cell($valueW,4,$b['nazev'],0,1,'L');

            $pdf->SetFont('font','',9);
            $pdf->SetX($left);
            $pdf->Cell($labelW,4,'IBAN:',0,0,'L');
            $pdf->Cell($valueW,4,$b['iban'],0,1,'L');

            $pdf->SetX($left);
            $pdf->Cell($labelW,4,'SWIFT:',0,0,'L');
            $pdf->Cell($valueW,4,$b['swift'],0,1,'L');
        }

        /* ===== ODBÌRATEL ===== */
        $pdf->SetXY(107,30);

        $pdf->SetFont('font','',9);
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
            $pdf->SetFont('font','',9);
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
        $pdf->Rect(5,113,200,15);

        $pdf->Line(68,113,68,128);
        $pdf->Line(131,113,131,128);

        $pdf->SetXY(7,115);
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

    /* ========================================================== */

    private static function renderPolozkyHeader(FPDF $pdf): void
    {
        $w = [80,15,10,20,25,20,20];

        $pdf->SetFont('font','',8);
        $pdf->SetX(10);

        $pdf->Cell($w[0],7,'Popis položky',1,0,'C');
        $pdf->Cell($w[1],7,'Množství',1,0,'C');
        $pdf->Cell($w[2],7,'MJ',1,0,'C');
        $pdf->Cell($w[3],7,'Cena za MJ',1,0,'C');
        $pdf->Cell($w[4],7,'Celkem bez DPH',1,0,'C');
        $pdf->Cell($w[5],7,'DPH',1,0,'C');
        $pdf->Cell($w[6],7,'Celkem s DPH',1,1,'C');

        $pdf->SetFont('font','',8);
    }

    /* ========================================================== */
   private static function renderPolozky(FPDF $pdf, array $data): void
    {
        $pocitadlo = 0;
        $w = [80,15,10,20,25,20,20];

        // poèáteèní pozice tabulky
        $pdf->SetXY(7,130);

        self::renderPolozkyHeader($pdf);

        foreach ($data['polozky'] as $polozka) {

            if ($pocitadlo > 0 && $pocitadlo % self::POLOZEK_NA_STRANKU === 0) {
                $pdf->AddPage();
                self::frame($pdf);
                self::header($pdf,$data);
                $pdf->SetXY(7,130);
                self::renderPolozkyHeader($pdf);
            }

            $xStart = $pdf->GetX();
            $yStart = $pdf->GetY();

            $pdf->MultiCell($w[0],6,$polozka['popis'],1);

            $h = $pdf->GetY() - $yStart;

            $pdf->SetXY($xStart + $w[0], $yStart);

            $pdf->Cell($w[1],$h,$polozka['mnozstvi'],1,0,'C');
            $pdf->Cell($w[2],$h,$polozka['mj'],1,0,'C');
            $pdf->Cell($w[3],$h,number_format($polozka['cena_za_mj'],2,',',' '),1,0,'C');
            $pdf->Cell($w[4],$h,number_format($polozka['bez_dph'],2,',',' '),1,0,'C');
            $pdf->Cell($w[5],$h,$polozka['dph_sazba'].'%',1,0,'C');
            $pdf->Cell($w[6],$h,number_format($polozka['s_dph'],2,',',' '),1,1,'C');

            $pocitadlo++;
        }

        /*
        ========================
        ØÁDEK CELKEM
        ========================
        */

        $s = $data['soucty'];

        $pdf->SetFont('font','',9);

        $pdf->Cell(80+15+10+20,7,'Celkem:',1,0,'R');
        $pdf->Cell(25,7,number_format($s['bez_dph'],2,',',' '),1,0,'C');
        $pdf->Cell(20,7,number_format($s['dph'],2,',',' '),1,0,'C');
        $pdf->Cell(20,7,number_format($s['s_dph'],2,',',' '),1,1,'C');

        $pdf->SetFont('font','',9);

        /*
        ========================
        POZNÁMKA POD TABULKOU
        ========================
        */

        if (!empty($data['poznamka'])) {
            $pdf->Ln(4);
            $pdf->MultiCell(0,5,$data['poznamka']);
        }
    }


    /* ========================================================== */
    private static function bottom(FPDF $pdf,$data)
    {
        $s = $data['soucty'];

        $y = 245;

        $pageX = 5;
        $pageWidth = 200;
        $colWidth = $pageWidth / 3;

        $col1 = $pageX;
        $col2 = $pageX + $colWidth;
        $col3 = $pageX + ($colWidth * 2);

        // ===== hlavní rám =====
        $pdf->Rect($pageX,$y,$pageWidth,47);

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
        $pdf->Cell($colWidth - 6,6,'Pøevzal:',0,1);


        /*
        =====================
        PRAVÝ SLOUPEC – SOUÈTY
        =====================
        */

        $x  = $col3;
        $yy = $y;

        $labelW = $colWidth - 25;
        $valueW = 25;
        $rowH   = 7;

        /*
        ---------------------
        HORNÍ TABULKA SOUÈTÙ
        ---------------------
        */

        $pdf->SetFont('font','',9);

        // Bez DPH
        $pdf->SetXY($x,$yy);
        $pdf->Cell($labelW,$rowH,'Celková èástka bez DPH:',1,0);
        $pdf->Cell($valueW,$rowH,number_format($s['bez_dph'],2,',',' ').' Kè',1,1,'R');

        // DPH
        $pdf->SetX($x);
        $pdf->Cell($labelW,$rowH,'DPH:',1,0);
        $pdf->Cell($valueW,$rowH,number_format($s['dph'],2,',',' ').' Kè',1,1,'R');

        // S DPH
        $pdf->SetX($x);
        $pdf->Cell($labelW,$rowH,'Celková èástka s DPH:',1,0);
        $pdf->Cell($valueW,$rowH,number_format($s['s_dph'],2,',',' ').' Kè',1,1,'R');

        /*
        ---------------------
        CELKEM
        ---------------------
        */

        $yy = $pdf->GetY();

        // popisek
        $pdf->SetFont('font','',9);
        $pdf->SetXY($x,$yy);
        $pdf->Cell($colWidth,6,'Celkem:',0,1,'L');

        // velká èástka
        $yy += 6;
        $pdf->SetFont('font','B',16);
        $pdf->SetXY($x,$yy);
        $pdf->Cell(
            $colWidth,
            10,
            number_format($s['s_dph'],2,',',' ').' Kè',
            0,
            1,
            'R'
        );

        // èástka slovy
        $yy += 10;
        $pdf->SetFont('font','',9);
        $pdf->SetXY($x,$yy);
        $pdf->MultiCell(
            $colWidth,
            5,
            $s['slovy'],
            0,
            'R'
        );




    }



}
