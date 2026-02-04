<?php

class Faktura
{
     private const POLOZEK_NA_STRANKU = 10;

    /**
     * Vygeneruje PDF faktury (layout ve stylu dodacího listu)
     */
    public static function create(FPDF $pdf, array $data): void
    {
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 20);

        self::renderHeader($pdf, $data);
        self::renderOdberatel($pdf, $data);
        self::renderPolozky($pdf, $data);
        self::renderSoucty($pdf, $data);
        self::renderFooter($pdf, $data);
    }

    /* ============================================================
     * HLAVIÈKA
     * ============================================================ */
    private static function renderHeader(FPDF $pdf, array $data): void
    {
        // NÁZEV DOKLADU
        $pdf->SetFont('font', 'B', 16);
        $pdf->Cell(
            0,
            8,
            textUtf8Win1250('FAKTURA ' . $data['cislo_dokladu']),
            0,
            1,
            'L'
        );
        $pdf->Ln(2);

        // DODAVATEL
        $dod = $data['dodavatel'];

        $pdf->SetFont('font', 'B', 10);
        $pdf->Cell(0, 5, textUtf8Win1250($dod['jmeno']), 0, 1);

        $pdf->SetFont('font', '', 9);
        $pdf->Cell(0, 4, textUtf8Win1250($dod['ulice']), 0, 1);
        $pdf->Cell(0, 4, textUtf8Win1250($dod['psc'] . ' ' . $dod['mesto']), 0, 1);
        $pdf->Cell(0, 4, textUtf8Win1250($dod['stat']), 0, 1);

        $pdf->Ln(1);
        $pdf->Cell(0, 4, 'IÈO: ' . $dod['ico'] . ' | DIÈ: ' . $dod['dic'], 0, 1);

        if (!empty($dod['telefon']) || !empty($dod['email'])) {
            $pdf->Cell(
                0,
                4,
                'TEL: ' . $dod['telefon'] . ' | EMAIL: ' . $dod['email'],
                0,
                1
            );
        }

        if (!empty($dod['web'])) {
            $pdf->Cell(0, 4, 'WEB: ' . $dod['web'], 0, 1);
        }

        if (!empty($dod['registrace'])) {
            $pdf->Ln(1);
            $pdf->MultiCell(0, 4, textUtf8Win1250($dod['registrace']));
        }

        // BANKA
        if (!empty($data['banka'])) {
            $banka = $data['banka'];
            $pdf->Ln(3);
            $pdf->Cell(0, 4, 'ÚÈET: ' . $banka['ucet'], 0, 1);
            $pdf->Cell(0, 4, 'BANKA: ' . $banka['nazev'], 0, 1);
            $pdf->Cell(0, 4, 'IBAN: ' . $banka['iban'], 0, 1);
            $pdf->Cell(0, 4, 'SWIFT: ' . $banka['swift'], 0, 1);
        }

        $pdf->Ln(6);
    }

    /* ============================================================
     * ODBÌRATEL + DODACÍ ADRESA
     * ============================================================ */
    private static function renderOdberatel(FPDF $pdf, array $data): void
    {
        // ODBÌRATEL
        $pdf->SetFont('font', 'B', 10);
        $pdf->Cell(0, 5, 'Fakturováno:', 0, 1);

        $odb = $data['odberatel'];

        $pdf->SetFont('font', '', 9);
        $pdf->Cell(0, 4, textUtf8Win1250($odb['jmeno']), 0, 1);
        $pdf->Cell(0, 4, textUtf8Win1250($odb['ulice']), 0, 1);
        $pdf->Cell(0, 4, textUtf8Win1250($odb['psc'] . ' ' . $odb['mesto']), 0, 1);
        $pdf->Cell(0, 4, textUtf8Win1250($odb['stat']), 0, 1);

        $pdf->Ln(4);

        // DODACÍ ADRESA
        if (!empty($data['dodaci_adresa'])) {
            $pdf->SetFont('font', 'B', 10);
            $pdf->Cell(0, 5, 'Dodací adresa:', 0, 1);

            $da = $data['dodaci_adresa'];

            $pdf->SetFont('font', '', 9);
            $pdf->Cell(0, 4, textUtf8Win1250($da['jmeno']), 0, 1);
            $pdf->Cell(0, 4, textUtf8Win1250($da['ulice']), 0, 1);
            $pdf->Cell(0, 4, textUtf8Win1250($da['psc'] . ' ' . $da['mesto']), 0, 1);
        }

        $pdf->Ln(4);

        // DATA DOKLADU
        $pdf->Cell(0, 4, 'Datum vystavení: ' . $data['datum_vystaveni'], 0, 1);

        if (!empty($data['objednavka_cislo'])) {
            $pdf->Cell(0, 4, 'Objednávka èíslo: ' . $data['objednavka_cislo'], 0, 1);
        }

        if (!empty($data['faktura_cislo'])) {
            $pdf->Cell(0, 4, 'Faktura èíslo: ' . $data['faktura_cislo'], 0, 1);
        }

        $pdf->Ln(6);
    }

    /* ============================================================
     * TABULKA POLOŽEK
     * ============================================================ */
    private static function renderPolozky(FPDF $pdf, array $data): void
    {
        $pocitadlo = 0;

        // HLAVIÈKA TABULKY (1. STRÁNKA)
        self::renderPolozkyHeader($pdf);

        foreach ($data['polozky'] as $polozka) {

            // ?? NOVÁ STRÁNKA PO 10 POLOŽKÁCH
            if ($pocitadlo > 0 && $pocitadlo % self::POLOZEK_NA_STRANKU === 0) {
                $pdf->AddPage();
                self::renderPolozkyHeader($pdf);
            }

            $yStart = $pdf->GetY();

            $pdf->MultiCell(70, 5, textUtf8Win1250($polozka['popis']));
            $height = $pdf->GetY() - $yStart;

            $pdf->SetXY(85, $yStart);
            $pdf->Cell(15, $height, $polozka['mnozstvi'], 0, 0, 'R');
            $pdf->Cell(10, $height, $polozka['mj'], 0, 0, 'R');
            $pdf->Cell(25, $height, number_format($polozka['cena_za_mj'], 2, ',', ' '), 0, 0, 'R');
            $pdf->Cell(30, $height, number_format($polozka['bez_dph'], 2, ',', ' '), 0, 0, 'R');
            $pdf->Cell(10, $height, $polozka['dph_sazba'] . '%', 0, 0, 'R');
            $pdf->Cell(30, $height, number_format($polozka['s_dph'], 2, ',', ' '), 0, 1, 'R');

            $pocitadlo++;
        }
    }

    /* ============================================================
     * HLAVIÈKA TABULKY POLOŽEK (ZNOVUPOUŽITELNÁ)
     * ============================================================ */
     private static function renderPolozkyHeader(FPDF $pdf): void
    {
        $pdf->SetFont('font', 'B', 9);
        $pdf->Cell(70, 6, 'Popis položky');
        $pdf->Cell(15, 6, 'Množství', 0, 0, 'R');
        $pdf->Cell(10, 6, 'MJ', 0, 0, 'R');
        $pdf->Cell(25, 6, 'Cena/MJ', 0, 0, 'R');
        $pdf->Cell(30, 6, 'Bez DPH', 0, 0, 'R');
        $pdf->Cell(10, 6, 'DPH', 0, 0, 'R');
        $pdf->Cell(30, 6, 'Celkem s DPH', 0, 1, 'R');

        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->SetFont('font', '', 9);
    }

    /* ============================================================
     * SOUÈTY
     * ============================================================ */
    private static function renderSoucty(FPDF $pdf, array $data): void
    {
        $s = $data['soucty'];

        $pdf->Ln(6);
        $pdf->SetFont('font', '', 9);

        $pdf->Cell(120);
        $pdf->Cell(40, 5, 'Celkem bez DPH:', 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($s['bez_dph'], 2, ',', ' ') . ' Kè', 0, 1, 'R');

        $pdf->Cell(120);
        $pdf->Cell(40, 5, 'DPH:', 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($s['dph'], 2, ',', ' ') . ' Kè', 0, 1, 'R');

        $pdf->SetFont('font', 'B', 10);
        $pdf->Cell(120);
        $pdf->Cell(40, 6, 'Celkem s DPH:', 0, 0, 'R');
        $pdf->Cell(35, 6, number_format($s['s_dph'], 2, ',', ' ') . ' Kè', 0, 1, 'R');

        $pdf->Ln(2);
        $pdf->SetFont('font', '', 9);
        $pdf->Cell(0, 5, textUtf8Win1250($s['slovy']));
    }

    /* ============================================================
     * PATIÈKA
     * ============================================================ */
    private static function renderFooter(FPDF $pdf, array $data): void
    {
        if (!empty($data['poznamka'])) {
            $pdf->Ln(8);
            $pdf->SetFont('font', '', 8);
            $pdf->MultiCell(0, 4, textUtf8Win1250($data['poznamka']));
        }

        $pdf->Ln(10);
        $pdf->SetFont('font', '', 9);

        $pdf->Cell(80, 5, 'Vystavil:', 0, 0);
        $pdf->Cell(80, 5, 'Pøevzal:', 0, 1);

        $pdf->Ln(12);
        $pdf->Cell(80, 5, '_________________________', 0, 0);
        $pdf->Cell(80, 5, '_________________________', 0, 1);
    }
}
