<?php

require_once __DIR__."/../library/Vakit.php";

$vakit = new Vakit();

$_response = '';
$_ulke = null;
$_sehir = null;
$_ilce = null;

if(isset($_GET['get'])){
    if(isset($_GET['ulke'])){
        $_ulke = $_GET['ulke'];
    }

    if(isset($_GET['sehir'])){
        $_sehir = $_GET['sehir'];
    }

    if(isset($_GET['ilce'])){
        $_ilce = $_GET['ilce'];
    }

    if($_GET['get'] == 'vakit'){
        $_response = $vakit->vakitGetir($_ulke, $_sehir, $_ilce);
    }
    elseif($_GET['get'] == 'ulkeler'){
        $_response = $vakit->ulkeler('json');
    }
    elseif($_GET['get'] == 'sehirler'){
        $_response = $vakit->sehirler($_ulke, 'json');
    }
    elseif($_GET['get'] == 'ilceler'){
        $_response = $vakit->ilceler($_sehir, 'json');
    }

    header('Content-Type: application/json');
    echo $_response;
}