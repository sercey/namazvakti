<?php

require_once "models/GunlukVakit.php";

/**
 * Diyanet'ten verileri ceker
 * Class Diyanet
 */
class Diyanet
{
    public $requestUrl = 'http://www.diyanet.gov.tr/PrayerTime/PrayerTimesSet';

    public $ulke = '2';

    public $sehir;

    public $ilce;

    /**
     * Bi ara lazım olur
     */
    public function __construct()
    {
    }

    /**
     * @return GunlukVakit|bool
     */
    public function vakitGetir()
    {
        $data = array(
            "countryName"	=> $this->ulke,
            "name"			=> $this->sehir,
            "stateName"		=> $this->ilce
        );

        $data = json_encode($data);

        $curlCevap = $this->curlRequest($data);

        $_return = false;

        if($curlCevap['sonuc'] == '1'){
            $vakit = new GunlukVakit();
            $vakit->tarih = date('Y-m-d');
            $vakit->ulke = $this->ulke;
            $vakit->sehir = $this->sehir;
            $vakit->ilce = $this->ilce;
            $vakit->imsak = $curlCevap['veri']['Imsak'];
            $vakit->gunes = $curlCevap['veri']['Gunes'];
            $vakit->ogle = $curlCevap['veri']['Ogle'];
            $vakit->ikindi = $curlCevap['veri']['Ikindi'];
            $vakit->aksam = $curlCevap['veri']['Aksam'];
            $vakit->yatsi = $curlCevap['veri']['Yatsi'];
            $vakit->kible = $curlCevap['veri']['KibleSaati'];

            $_return = $vakit;
        }

        return $_return;
    }

    /**
     * @param $data
     * @return array
     */
    private function curlRequest($data)
    {
        $url = sprintf( $this->requestUrl, $data );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $data ) ) );
        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );

        $bilgi = curl_getinfo( $ch );
        $veri = curl_exec( $ch );

        if( $bilgi['http_code'] == 200 )
        {
            $sonuc = array(
                'sonuc'	=> '1',
                'veri'	=> json_decode( $veri, TRUE )
            );
        }
        elseif ($bilgi['http_code'] == 0 )
        {
            if( $veri != '[]' )
            {
                $sonuc = array(
                    'sonuc'	=> '1',
                    'veri'	=> json_decode( $veri, TRUE )
                );
            } else {
                $sonuc = array(
                    'sonuc'	=> '0',
                    'veri'	=> array()
                );
            }
        }
        else
        {
            $sonuc = array(
                'sonuc'	=> '0',
                'veri'	=> array()
            );
        }
        curl_close( $ch );
        return $sonuc;
    }
}