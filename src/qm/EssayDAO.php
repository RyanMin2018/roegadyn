<?php
namespace src\qm;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class EssayDAO {
    
    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }
    
    public function regist($uid, $context) {
        $query = "DELETE FROM QM_ESSAY_TB WHERE SUBJECT_SEQ = '".$uid."';";
        $this->db->query($query);
        $query = "INSERT INTO QM_ESSAY_TB (SUBJECT_SEQ, CONTENT, REG_DT) VALUES ('".$uid."', '".$context."', NOW());";
        return $this->db->getSeqAfterQuery($query);
    }


}

