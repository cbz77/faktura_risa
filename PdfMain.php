<?php

use FPDF;

require_once(__DIR__ . '/fpdf-1.82/fpdf.php');

class PdfMain extends FPDF
{
    public array $stranka = ['sirka' => 210, 'vyska' => 297];
    private array $data_pro_fakturu;

    public function __construct()
    {
        parent::__construct();

        $this->AliasNbPages('^');
        $this->AddFont('font', '', 'arial.php');
        $this->AddFont('font', 'B', 'arialbd.php');
        $this->AddFont('font', 'BI', 'arialbi.php');
        $this->AddFont('font', 'I', 'ariali.php');
    }

    public function setData(array $data)
    {
        $this->data_pro_fakturu = $data;
    }

    // 👉 POUZE číslování stránek
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('font', '', 8);
        $this->Cell(
            0,
            10,
            textUtf8Win1250('Strana ') . $this->PageNo() . ' / ^',
            0,
            0,
            'C'
        );
    }
}
