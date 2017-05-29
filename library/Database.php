<?php

require_once "models/GunlukVakit.php";

/**
 * Class Database
 */
class Database
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var string
     */
    public $database;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var PDO
     */
    public $db;

    /**
     * @param array $params Veritabanı Bağlantı Parametreleri
     */
    public function __construct($params = array())
    {
        $this->host = $params['host'];
        $this->database = $params['database'];
        $this->username = $params['username'];
        $this->password = $params['password'];

        $this->connect();
    }

    /**
     * Veritabanına bağlan
     */
    private function connect()
    {
        try{
            $this->db = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset=utf8', $this->username, $this->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $ex){
            throw new PDOException('Veritabanı bağlantısı yapılamadı: '.$ex->getMessage(), 0, $ex);
        }
    }

    /**
     * @param $tarih
     * @param int $ulke
     * @param null $sehir
     * @param null $ilce
     * @return mixed
     */
    public function vakitGetir($tarih, $ulke = 2, $sehir = null, $ilce = null)
    {
        $query = $this->db->prepare('SELECT * FROM gunluk_vakit WHERE tarih = :tarih AND ulke = :ulke AND sehir = :sehir AND ilce = :ilce');

        $query->execute(array(
            'tarih' => $tarih,
            'ulke' => $ulke,
            'sehir' => $sehir,
            'ilce' => $ilce
        ));

        return $sonuc = $query->fetchObject('GunlukVakit');
    }

    /**
     * @param GunlukVakit $vakit
     * @return bool
     */
    public function vakitEkle(GunlukVakit $vakit)
    {
        $query = $this->db->prepare('INSERT INTO gunluk_vakit(tarih, ulke, sehir, ilce, imsak, gunes, ogle, ikindi, aksam, yatsi, kible) VALUES(:tarih, :ulke, :sehir, :ilce, :imsak, :gunes, :ogle, :ikindi, :aksam, :yatsi, :kible)');

        $exec = $query->execute(array(
            'tarih' => $vakit->tarih,
            'ulke' => $vakit->ulke,
            'sehir' => $vakit->sehir,
            'ilce' => $vakit->ilce,
            'imsak' => $vakit->imsak,
            'gunes' => $vakit->gunes,
            'ogle' => $vakit->ogle,
            'ikindi' => $vakit->ikindi,
            'aksam' => $vakit->aksam,
            'yatsi' => $vakit->yatsi,
            'kible' => $vakit->kible,
        ));

        if($exec){
            return true;
        }
        else{
            return false;
        }
    }
}