<?php
include_once 'config.php';
class ConnectDataBase
{ 
    private $host       = DB_HOST;
    private $port       = DB_PORT;
    private $dbname     = DB_NAME;
    private $dbname_sh  = DB_SCHEMA;
    private $charset    = DB_CHARSET;
    private $user       = DB_USER;
    private $pass       = DB_PASSWORD;
    //public $con;
    function __construct()
	{
        $name = 'LOL';
        //**
	}
    public function connect()
    {
		$con=new PDO("mysql:host=".DB_HOST."; port=".DB_PORT."; charset=".DB_CHARSET, DB_USER, DB_PASSWORD);
		return $con;
		unset ($con);
	}
    public function checkDb()
    {
		$con=$this->connect();
		$ret = $con->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '".DB_NAME."'");/*sprawdzam czy baza istnieje*/
		$res = $ret->fetch(PDO::FETCH_ASSOC);
		return $res ?  true : false;
	}
	public function connectDb()
    {
        if ($this->checkDb()=== true) {
            $con = new PDO("mysql:host=".DB_HOST."; port=".DB_PORT."; dbname=".DB_NAME."; charset=".DB_CHARSET, DB_USER, DB_PASSWORD);
            return $con;
        } else {
            return 'error_DB_not_exist';
        }
	}
}