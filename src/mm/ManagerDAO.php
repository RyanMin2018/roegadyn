<?php
namespace src\mm;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class ManagerDAO {
    
    private $db;
    
    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        $this->db  = new \src\db\DbConn();
    }
     
    public function logon($logid, $logpw) {
        $isSuccess = false;
        $query ='INSERT INTO MM_MANAGER_LOG_TB (USER_ID, LOG_DT) SELECT USER_ID, NOW() FROM MM_MANAGER_TB WHERE USER_ID="'.$logid.'" AND USER_PW=PASSWORD("'.$logpw.'");';
        $i = $this->db->getSeqAfterQuery($query);
        if (isset($i) && $i>0) {
            session_cache_expire(1000*60*4);
            $_SESSION["USER_ID"] = $logid;
            $isSuccess = true;
        }
        return $isSuccess;
    }
}

