<?php
setlocale(LC_TIME, 'tr_TR.UTF-8');
date_default_timezone_set("Europe/Istanbul");

require_once 'library/Vakit.php';


$vakit = new Vakit();

print_r($vakit->ulkeler());

echo date('Y-m-d H:i:s');
