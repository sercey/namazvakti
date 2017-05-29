<?php

require_once "library/Vakit.php";

$vakit = new Vakit();

if (count($_GET) > 0)
{
    switch($_GET['islem'])
    {
        case 'ulke' :
            $veri = $vakit->ulkeler('json');
            echo $veri;
            break;

        case 'sehir' :
            $veri = $vakit->sehirler($_GET['ulke'], 'json');
            echo $veri;
            break;

        case 'ilce' :
            $veri = $vakit->ilceler($_GET['sehir'], 'json');
            echo $veri;
            break;

        case 'vakit' :
            $veri = $vakit->vakitGetir( $_GET['ulke'], $_GET['sehir'], $_GET['ilce']);
            print_r( $veri );
            break;
    }
}

//echo json_encode($vakit->dbTest());