<?php
namespace src\mgr;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class LockTestDAO {
    
    private $db;
    
    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        $this->db  = new \src\db\DbConn();
    }
    
    public function currentAuthKey() {
        $query = 'SELECT AUTHKEY, ROUND((UNIX_TIMESTAMP(EXPIRE_DT)-UNIX_TIMESTAMP(NOW()))/60, 0) AS REST_HOUR FROM AUTHKEY WHERE EXPIRE_DT > NOW();';
        $result = $this->db->query($query);
        $row    = mysqli_fetch_object($result);
        if (isset($row)) {
            return array($row->AUTHKEY, $row->REST_HOUR);
        } else {
            return NULL;
        }
    }
    
    public function updateAuthKey($AuthKey, $addHour) {
        $query = 'UPDATE AUTHKEY SET AUTHKEY="'.$AuthKey.'", EXPIRE_DT = DATE_ADD(NOW(), INTERVAL '.$addHour.' HOUR);';
        $this->db->query($query);
    }
    
}

