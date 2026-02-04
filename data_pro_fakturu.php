<?php

$data_pro_fakturu = [

    // HLAVIÈKA DOKLADU
    'cislo_dokladu' => 'DL-2023-0001',
    'typ_dokladu' => 'faktura',
    'datum_vystaveni' => '28.4.2023',
    'objednavka_cislo' => null,
    'faktura_cislo' => null,

    // DODAVATEL
    'dodavatel' => [
        'jmeno' => 'Hammer Masters s.r.o.',
        'ulice' => 'U Vodárny 1081/2',
        'psc' => '530 09',
        'mesto' => 'Pardubice',
        'stat' => 'Èeská republika',
        'ico' => '83125649',
        'dic' => 'CZ83125649',
        'platce_dph' => true,
        'telefon' => '+420 (466) 2200 0001',
        'email' => 'info@hammermasters.cz',
        'web' => 'www.hammermasters.cz',
        'registrace' => 'Spoleènost zapsaná do OR Krajského soudu v Hradci Králové, spisová znaèka C 1111.',
    ],

    // BANKA
    'banka' => [
        'ucet' => '2000249999/0800',
        'nazev' => 'Èeská spoøitelna, a.s.',
        'iban' => 'CZ3408000000002000249999',
        'swift' => 'GIBACZPX',
    ],

    // ODBÌRATEL
    'odberatel' => [
        'jmeno' => 'Bytový komplex Sluneèná zahrada, s.r.o.',
        'ulice' => 'Kvìtnová 781/15',
        'psc' => '140 00',
        'mesto' => 'Praha 4',
        'stat' => 'Èeská republika',
    ],

    // DODACÍ ADRESA
    'dodaci_adresa' => [
        'jmeno' => 'Bytový komplex Sluneèná zahrada',
        'ulice' => 'U Sluneèního vršku 837',
        'psc' => '140 00',
        'mesto' => 'Praha 4',
    ],

    // POLOŽKY – PØESNÌ PODLE TABULKY
    'polozky' => [
        [
            'popis' => 'Cihla broušená Porotherm (500x250x150 mm)',
            'mnozstvi' => 250,
            'mj' => 'ks',
            'cena_za_mj' => 82.64,
            'bez_dph' => 20661.16,
            'dph_sazba' => 21,
            's_dph' => 25000.00,
        ],
        [
            'popis' => 'Hydroizolaèní fólie (30 m2)',
            'mnozstvi' => 10,
            'mj' => 'ks',
            'cena_za_mj' => 6694.21,
            'bez_dph' => 66942.15,
            'dph_sazba' => 21,
            's_dph' => 81000.00,
        ],
        [
            'popis' => 'Hliníkové žebøíky (4 m)',
            'mnozstvi' => 3,
            'mj' => 'ks',
            'cena_za_mj' => 2148.76,
            'bez_dph' => 6446.28,
            'dph_sazba' => 21,
            's_dph' => 7800.00,
        ],
        [
            'popis' => 'Betonové tvarovky (300x300x60 mm)',
            'mnozstvi' => 150,
            'mj' => 'ks',
            'cena_za_mj' => 61.98,
            'bez_dph' => 9297.52,
            'dph_sazba' => 21,
            's_dph' => 11250.00,
        ],
        [
            'popis' => 'Omítka pastovitá silikon bílá (25 kg balení)',
            'mnozstvi' => 20,
            'mj' => 'ks',
            'cena_za_mj' => 1280.99,
            'bez_dph' => 25619.83,
            'dph_sazba' => 21,
            's_dph' => 31000.00,
        ],
        [
            'popis' => 'Cihla broušená Porotherm (500x250x150 mm)',
            'mnozstvi' => 250,
            'mj' => 'ks',
            'cena_za_mj' => 82.64,
            'bez_dph' => 20661.16,
            'dph_sazba' => 21,
            's_dph' => 25000.00,
        ],
        [
            'popis' => 'Hydroizolaèní fólie (30 m2)',
            'mnozstvi' => 10,
            'mj' => 'ks',
            'cena_za_mj' => 6694.21,
            'bez_dph' => 66942.15,
            'dph_sazba' => 21,
            's_dph' => 81000.00,
        ],
        [
            'popis' => 'Hliníkové žebøíky (4 m)',
            'mnozstvi' => 3,
            'mj' => 'ks',
            'cena_za_mj' => 2148.76,
            'bez_dph' => 6446.28,
            'dph_sazba' => 21,
            's_dph' => 7800.00,
        ],
        [
            'popis' => 'Betonové tvarovky (300x300x60 mm)',
            'mnozstvi' => 150,
            'mj' => 'ks',
            'cena_za_mj' => 61.98,
            'bez_dph' => 9297.52,
            'dph_sazba' => 21,
            's_dph' => 11250.00,
        ],
        [
            'popis' => 'Omítka pastovitá silikon bílá (25 kg balení)',
            'mnozstvi' => 20,
            'mj' => 'ks',
            'cena_za_mj' => 1280.99,
            'bez_dph' => 25619.83,
            'dph_sazba' => 21,
            's_dph' => 31000.00,
        ],
        [
            'popis' => 'Cihla broušená Porotherm (500x250x150 mm)',
            'mnozstvi' => 250,
            'mj' => 'ks',
            'cena_za_mj' => 82.64,
            'bez_dph' => 20661.16,
            'dph_sazba' => 21,
            's_dph' => 25000.00,
        ],
        [
            'popis' => 'Hydroizolaèní fólie (30 m2)',
            'mnozstvi' => 10,
            'mj' => 'ks',
            'cena_za_mj' => 6694.21,
            'bez_dph' => 66942.15,
            'dph_sazba' => 21,
            's_dph' => 81000.00,
        ],
        [
            'popis' => 'Hliníkové žebøíky (4 m)',
            'mnozstvi' => 3,
            'mj' => 'ks',
            'cena_za_mj' => 2148.76,
            'bez_dph' => 6446.28,
            'dph_sazba' => 21,
            's_dph' => 7800.00,
        ],
        [
            'popis' => 'Betonové tvarovky (300x300x60 mm)',
            'mnozstvi' => 150,
            'mj' => 'ks',
            'cena_za_mj' => 61.98,
            'bez_dph' => 9297.52,
            'dph_sazba' => 21,
            's_dph' => 11250.00,
        ],
        [
            'popis' => 'Omítka pastovitá silikon bílá (25 kg balení)',
            'mnozstvi' => 20,
            'mj' => 'ks',
            'cena_za_mj' => 1280.99,
            'bez_dph' => 25619.83,
            'dph_sazba' => 21,
            's_dph' => 31000.00,
        ],
    ],

    // SOUÈTY
    'soucty' => [
        'bez_dph' => 128966.94,
        'dph' => 27083.06,
        's_dph' => 156050.00,
        'slovy' => 'sto padesát šest tisíc padesát korun',
    ],

    // PATIÈKA
    'poznamka' =>
        'Dodavatel neodpovídá za škody vzniklé neodborným zacházením, skladováním nebo montáží materiálu.
        Zboží zùstává vlastnictvím dodavatele až do úplného zaplacení všech pohledávek.',
];

