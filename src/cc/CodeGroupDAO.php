<?php
namespace src\cc;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class CodeGroupDAO {
    
    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }
    
    public function getCodeGroupList() {
        $rows;
        $arr = $this->db->query("SELECT * FROM CC_CODE_GROUP_TB");
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }

}

