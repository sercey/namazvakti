<?php

require_once "Database.php";
require_once "Diyanet.php";
require_once "models/GunlukVakit.php";

/**
 * Class Vakit
 */
class Vakit
{
    /**
     * @param $ulke
     * @param null $sehir
     * @param null $ilce
     * @return bool|GunlukVakit
     */
    public function vakitGetir($ulke, $sehir = null, $ilce = null)
    {
        $_return = array();

        try{
            $db = new Database(array(
                'host' => 'localhost',
                'database' => 'cozumeks_prayer',
                'username' => 'root',
                'password' => 'root',
            ));

            if(empty($ilce)){
                $ilce = $sehir;
            }

            $fromDb = $db->vakitGetir(date('Y-m-d'), $ulke, $sehir, $ilce);

            $_return = array('result' => 'success', 'response' => $fromDb);

            if(empty($fromDb)){
                $diyanet = new Diyanet();
                $diyanet->ulke = $ulke;
                $diyanet->sehir = $sehir;
                $diyanet->ilce = $ilce;

                $fromDiyanet = $diyanet->vakitGetir();
                if($fromDiyanet != false){
                    $_return = array('result' => 'success', 'response' => $fromDiyanet);
                    $db->vakitEkle($fromDiyanet);
                }
                else{
                    $_return = array('result' => 'error');
                }
            }
        }
        catch(Exception $ex){
            $_return = array('result' => 'error', 'response' => $ex->getMessage());
        }

        return json_encode($_return);
    }

    /**
     * Ülkesi verilen şehirleri çeker
     *
     * @param string $cikti Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
    public function ulkeler( $cikti='array' )
    {
        $dosyayolu = dirname(__FILE__);
        $ulkeler_db = file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ulkeler.ndb' );

        $ulkeler = json_decode( $ulkeler_db, TRUE);
        $sonuc = array(
            'durum' => 'hata',
            'veri' => array()
        );

        foreach( $ulkeler as $key => $value )
        {
            $sonuc['durum'] = 'basarili';
            $sonuc['veri'][$key] = $value;
        }

        $yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
        return $yazdir;
    }

    /**
     * Ülkesi verilen şehirleri çeker
     *
     * @param string $ulke Verisi çekilecek ülkeyi belirler
     * @param string $cikti Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
    public function sehirler( $ulke=NULL, $cikti='array' )
    {
        $dosyayolu = dirname(__FILE__);
        $sehirler_db = file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sehirler.ndb' );

        //$ulke = is_null( $ulke ) === TRUE ? $this->ulke : $ulke;

        // şehirleri arraya çevir
        $sehirler = json_decode( $sehirler_db, TRUE);

        $sonuc = array(
            'durum' => 'hata',
            'veri' => array()
        );

        if ( array_key_exists( $ulke, $sehirler ) )
        {
            $sonuc['durum'] = 'basarili';
            $sonuc['veri'] = $sehirler[$ulke];
        }

        $yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
        return $yazdir;
    }

    /**
     * Şehri verilen ilçeleri çeker
     *
     * @param string $sehir Verisi çekilecek şehri belirler
     * @param string $cikti Verinin dışarıya nasıl çıktılanacağını belirtir
     * @return array Sonucu bir dizi olarak döndürür
     */
    public function ilceler( $sehir=NULL, $cikti='array' )
    {
        $dosyayolu = dirname(__FILE__);
        $ilceler_db = file_get_contents( $dosyayolu . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'ilceler.ndb' );

        //$sehir = is_null( $sehir ) === TRUE ? $this->sehir : $sehir;

        // ilçeleri alalım
        $ilceler = json_decode( $ilceler_db, TRUE );

        $sonuc = array(
            'durum' => 'hata',
            'veri' => array()
        );

        if( array_key_exists( $sehir, $ilceler ) )
        {
            $sonuc['durum'] = 'basarili';
            $sonuc['veri'] = $ilceler[$sehir];
        }


        $yazdir = $cikti == 'array' ? $sonuc : json_encode( $sonuc );
        return $yazdir;
    }

    public function dbTest()
    {
        $_return = false;

        $db = new Database(array(
            'host' => 'localhost',
            'database' => 'namaz_vakit',
            'username' => 'root',
            'password' => 'root',
        ));

        //$fromDb = $db->vakitGetir(date('Y-m-d'), 2, 520, 9335);
        $fromDb = $db->vakitGetir(date('Y-m-d'), 2, 506, 9206);
        $_return = $fromDb;

        if(empty($fromDb)){
            $diyanet = new Diyanet();
            $diyanet->sehir = 506;
            $diyanet->ilce = 9206;
            $fromDiyanet = $diyanet->vakitGetir();
            $_return = $fromDiyanet;

            $db->vakitEkle($fromDiyanet);
        }

        return $_return;
    }
}