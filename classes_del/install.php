<?php
include_once 'ConnectDataBase.php';
class InstallDataBase extends ConnectDataBase
{
    // private $host       = DB_HOST;
    // private $port       = DB_PORT;
    // private $dbname     = DB_NAME;
    // private $dbname_sh  = DB_SCHEMA;
    // private $charset    = DB_CHARSET;
    // private $user       = DB_USER;
    // private $pass       = DB_PASSWORD;
    function __construct()
    {
        parent::__construct();
        //$con2 = $this->con;
        $this->con = ConnectDataBase::connectDb();
    }
	public function __setTable($tab_name)
    {
		$this->table = $tab_name;
		$this->prefix = $tab_name[0].'_';
	}
	// public function connect()
    // {
		// $con = new PDO("mysql:host=".$this->host."; port=".$this->port."; charset=".$this->charset,$this->user,$this->pass);
		// return $con;
		// unset ($con);
	// }
	public function connectDb()
    {
		//$con = new PDO("mysql:host=".$this->host."; port=".$this->port."; dbname=".$this->dbname."; charset=".$this->charset,$this->user,$this->pass);
		return $this->con;
		//unset ($con);
	}
	// public function checkDb()
    // {
		// $con=$this->connect();
		// $ret = $con->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '".$this->dbname."'");/*sprawdzam czy baza istnieje*/
		// $res = $ret->fetch(PDO::FETCH_ASSOC);
		// return $res ?  true : false;
	// }

	// public function createDb()
    // {
		// if ($this->checkDb()=== false) {
			// $con=$this->connect();		
			// //$con->exec("CREATE DATABASE IF NOT EXISTS ".$this->dbname." charset=".$this->charset);
			// $con->exec("CREATE DATABASE IF NOT EXISTS ".$this->dbname." charset=".$this->charset." COLLATE=utf8_general_ci" );
			// unset ($con);
			// return true;
		// } elseif ($this->checkDb()=== true) {
			// return false;
		// }
	// }
    // public function rrmdir($dir) 
    // {
	// // do kasowania folderów plików i pod folderów
		// if (is_dir($dir)) {
			// $objects = scandir($dir);
			// foreach ($objects as $object) {
				// if ($object != "." && $object != "..") {
					// if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
				// }
			// }
			// reset($objects);
			// rmdir($dir);
		// }
	// }
	// public function deleteDb()
    // {
		// $con=$this->connect();
		// $result=$con->exec("DROP DATABASE `".$this->dbname."`"); //usowanie
		// unset ($con);
		// if($result) {
            // $this->rrmdir('data/');
			// return true;
		// } else {
			// return false;
		// }
	// }
    // public function show()
    // {
        // //$res = $this->con->fetchAll(PDO::FETCH_ASSOC);
        // $res = $this->con;
        // var_dump($res);
        // //return $res;
    // }
    public function show()
    {
		$con=$this->connectDb();
		$q = $con->query("SELECT * FROM `".$this->table."`");/*zwraca false jesli tablica nie istnieje*/
		unset ($con);
        if ($q) {
            $q = $q->fetch(PDO::FETCH_ASSOC);
            return var_dump($q);
        } else {
            return var_dump(false);
        }
	}
    public function createTbDynamicRow($arr_row, $arr_val)
    {
        // Tworze tabele tylko raz co pozwala klikać install bez konsekwencji
		$con = $this->connectDb();
		$res = $con->query(
            "SELECT 1 
            FROM ".$this->table
            );// Zwraca false jesli tablica nie istnieje
		if (!$res) {
            $columns='';
            foreach ($arr_row as $name => $val) {
                $columns .= '`'.$name.'` '.$val.',';
            }
            // Create table
			$res = $con->query(
                "CREATE TABLE IF NOT EXISTS `".$this->table."`(
                `".$this->prefix."id` INTEGER AUTO_INCREMENT,            
                ".$columns."
                `mod` INTEGER(2),
                PRIMARY KEY(`".$this->prefix."id`)
                )ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1"
                );
            ////nie może tu byc return bo sie dalej nie wykona
            if (! empty($arr_val)) {
                
                $field='';
                $value='';
                foreach ($arr_val as $name => $val) {
                    $field .= '`'.$name.'`,';
                    $value .= "'".$val."',";
                }
                // Create default record 
                $res = $con->query(
                    "INSERT INTO `".$this->table."`(
                    ".$field."
                    `mod`
                    ) VALUES (
                    ".$value."
                    '0'
                    )"
                    );
                return $res ? true : false;
            } else {
                return $res ? true : false;
            }
		} else {
			return false;
		}
    }
    public function addRec($arr_val){
        $con = $this->connectDb();
		$res = $con->query(
            "SELECT '".$this->prefix."id' 
            FROM ".$this->table
            );// Zwraca false jesli tablica nie istnieje
            $res = $res->fetch();
		if (! empty($res) ) {
            if (! empty($arr_val)) {
                    $field='';
                    $value='';
                    foreach ($arr_val as $name => $val) {
                        $field .= '`'.$name.'`,';
                        $value .= "'".$val."',";
                    }
                    // Create default record 
                    $res = $con->query(
                        "INSERT INTO `".$this->table."`(
                        ".$field."
                        `mod`
                        ) VALUES (
                        ".$value."
                        '0'
                        )"
                        );
                    return $res ? true : false;
                } else {
                    return $res ? true : false;
                }
        }
    }
    public function deleteTb($table)
    {
        $con = $this->connectDb();
        $res = $con->query('DROP TABLE `'.$table.'`');
        return var_dump($res ? true : false);
    }
    public function updateRow($row, $value, $where_id)//wgranie zawartości wywołane wewnątrz funkcji _getString
    {
        //zapis
        try{ 
            $con = $this->connectDB();
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $res = $con->query("UPDATE `".$this->table."` 
                SET
                `".$row."` = '".$value."'
                WHERE
                `".$this->prefix."id` = '".$where_id."'
                ");
            if ($res) {
                echo "<div class=\"center\" >Zapis: OK!</div>";
                echo "<div class=\"center\" >Last id: ".$con->lastInsertId()."</div>";
            } else {
                echo "<div class=\"center\" >Zapis: ERROR!</div>";
            }
        } 
        catch(PDOException $exception){ 
           return $exception->getMessage(); 
        } 
        unset($con);
    }
}
/**
/*  SHOW - amplifier selection program
**/
$obj_show = new InstallDataBase;
//$obj_show->__setTable('amplifiers');
//$obj_show->show();
/**
* Data Base created 
**/
$obj_install = new InstallDataBase;
if (isset($_POST['connect'])) {
	$obj_install->__setTable('amplifiers');
    $obj_install->show();
}
if (isset($_POST['del'])) {
	$obj_install->deleteTb('amplifiers');
}
if (isset($_POST['crt'])) {
    //$obj_install->createDb();
    $return = array();// array initiate
    $obj_install->__setTable('amplifiers');
    $data = date('Y-m-d H:i:s');
    $arr_row = array(
        'id_shop'                   =>'INTEGER(10)',
        'name'                      =>'TEXT',
        'model'                     =>'VARCHAR(100)',
        'link'                      =>'VARCHAR(500)',
        'cat'                       =>'VARCHAR(500)',
        'output'                    =>'VARCHAR(10)',
        'band'                      =>'VARCHAR(100)', /*GSM=2 EGSM=3 UMTS=4 DCS=5*//*pasmo*/
        'in_range'                  =>'VARCHAR(100)', /*50m=2 100m=3 300m=4 300m<=5*//*zasięg in*/
        'out_range'                 =>'VARCHAR(100)', /*0-1km=2 1-2km=3 2-5km=4 5-20km=5*//*zasięg out*/
        'add_data'                  =>'DATETIME',
        'update_data'               =>'DATETIME',
        'visibility'                =>'INTEGER(1) UNSIGNED'
        );
    $arr_val = array(
        'id_shop'                   =>111,
        'name'                      =>'WZMACNIACZ_NAME',
        'model'                     =>'DC-2000',
        'link'                      =>'https://new-electric.pl/pl/68-wzmacniacze',
        'cat'                       =>'https://new-electric.pl/pl/68-wzmacniacze',
        'output'                    =>'N', 
        'band'                      =>'2,3,4,5', 
        'in_range'                  =>'2', 
        'out_range'                 =>'2', 
        'add_data'                  =>$data, 
        'update_data'               =>$data, 
        'visibility'                =>'1'    
        );
    $return['amplifiers'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    
    // $obj_install->__setTable('category');
    // $arr_row = array(
        // 'category'                  =>'TEXT',
        // 'protect'                   =>'VARCHAR(20)', 
        // 'password'                  =>'VARCHAR(20)', 
        // 'c_visibility'              =>'INTEGER(1) UNSIGNED'
        // );
    // $arr_val = array();
    // $return['category'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    // $arr_val = array(
        // 'category'                  =>'Air Show Warszawa',
        // 'protect'                   =>'0', 
        // 'password'                  =>'', 
        // 'c_visibility'              =>'1'    
        // );
    // $return['category'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    // $obj_install->__setTable('category');
    // $arr_val = array(
        // 'category'                  =>'Air Show Radom',
        // 'protect'                   =>'0', 
        // 'password'                  =>'', 
        // 'c_visibility'              =>'1'    
        // );
    // $return['category'] = $obj_install->addRec($arr_val);
    // $arr_val = array(
        // 'category'                  =>'Air Shop',
        // 'protect'                   =>'0', 
        // 'password'                  =>'', 
        // 'c_visibility'              =>'1'    
        // );
    // $return['category'] = $obj_install->addRec($arr_val);
    var_dump($return);
}
// if (isset($_POST['del'])) {
	// $obj_install->deleteDb();
// }
/**
* Slider install
**/
if (isset($_POST['crt_slider'])) {
    $obj_install->createDb();
    $return = array();// array initiate
    $obj_install->__setTable('slider');
    $arr_row = array(
        'slider_name'               =>'TEXT',
        'slider_mime'               =>'VARCHAR(20)',
        'slider_src'                =>'TEXT',
        'slider_href'               =>'TEXT',
        'slider_alt'                =>'TEXT',
        'slider_title'              =>'TEXT',
        'slider_des'                =>'TEXT',
        's_visibility'              =>'INTEGER(1) UNSIGNED'
        );
    $arr_val = array();
    $return['slider'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    var_dump($return);
    $obj_install->__setTable('description');
    $arr_row = array(
        'home_des'                  =>'TEXT',
        'd_visibility'              =>'INTEGER(1) UNSIGNED'
        );
    $arr_val = array(
        'home_des'                  =>'<h2>O mnie </h2>',
        'd_visibility'              =>'1'    
        );
    $return['description'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    var_dump($return);
}
if (isset($_POST['del_slider'])) {
	$obj_install->deleteTb('slider');
    $obj_install->rrmdir('img/slider/images/');
    $obj_install->rrmdir('img/slider/tooltips/');
}
/** Update data **/
if (isset($_POST['updat_row'])) {
	$obj_install->__setTable('photos');
    for ($i = $_POST['start_id']; $i < $_POST['stop_id']; $i++) {
        $return['photos'] = $obj_install->updateRow('add_data', $_POST['updat_value'], $i);
        //var_dump($return);
    }
}
/** 
* User install
**/
if (isset($_POST['crt_user']) && $_POST['user_pass1'] == $_POST['user_pass2']) {
    $obj_install->createDb();
    $return = array();// array initiate
    $obj_install->__setTable('user');
    $arr_row = array(
        'user_name'                 =>'TEXT',
        'user_pass'                 =>'TEXT',
        's_visibility'              =>'INTEGER(1) UNSIGNED'
        );
    $arr_val = array(
        'user_name'                 =>$_POST['user_name'],
        'user_pass'                 =>md5($_POST['user_pass1']),
        's_visibility'              =>1
        );
    $return['user'] = $obj_install->createTbDynamicRow($arr_row, $arr_val);
    var_dump($return);

}
?>
<div class="center">
    Zarządzanie Bazą Danych
    <form name="install" enctype="multipart/form-data" action="" method="POST">
            <input class="input_cls" type="submit" name="crt" value="Create TB" />
            <input class="input_cls" type="submit" name="del" value="Delete TB" />
            <input class="input_cls" type="submit" name="connect" value="Connect" />
            <!--
            <input class="input_cls" type="submit" name="del" value="Delete DB" />
            
            <br />
            <input class="input_cls" type="submit" name="del_slider" value="Delete Slider" />
            <input class="input_cls" type="submit" name="crt_slider" value="Create Slider" />
            <br />
            <input class="input_cls" type="submit" name="del_subcategory" value="Delete Sub" />
            <input class="input_cls" type="submit" name="crt_subcategory" value="Create Sub" />
            <br />
            <input class="input_cls" type="submit" name="del_user" value="Delete User" />
            <input class="input_cls" type="submit" name="crt_user" value="Create User" />
            <input class="input_cls" type="text" name="user_name" placeholder="User Name" value="deoc" />
            <input class="input_cls" type="text" name="user_pass1" placeholder="Password" value="pio" />
            <input class="input_cls" type="text" name="user_pass2" placeholder="Password retry" value="pio" />
            <br />
            <input class="input_cls" type="text" name="updat_value" placeholder="updat_value" value="2016-11-01 00:00:00" />
            <input class="input_cls" type="text" name="start_id" placeholder="start_id" value="0" />
            <input class="input_cls" type="text" name="stop_id" placeholder="stop_id" value="96" />
            <input class="input_cls" type="submit" name="updat_row" value="Update" />
            <input class="input_cls" type="submit" name="connect" value="Connect" />
            -->
        
    </form>
</div>