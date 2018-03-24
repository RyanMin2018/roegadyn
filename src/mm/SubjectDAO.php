<?php
namespace src\mm;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class SubjectDAO {

    private $db;
    
    public function __construct() {
        if(!isset($_SESSION)) { session_start(); }
        $this->db  = new \src\db\DbConn();
    }
    
    public function regist($schoolcd, $grade, $gendercd) {
        $uid = 0;
        $query =        " INSERT INTO MM_SUBJECT_TB (SCHOOL_CD, GRADE, GENDER_CD, SEQ, JOIN_DT) ";
        $query = $query." SELECT '".$schoolcd."', '".$grade."', '".$gendercd."', IFNULL(MAX(SEQ), 0)+1, NOW() ";
        $query = $query."   FROM MM_SUBJECT_TB WHERE SCHOOL_CD = '".$schoolcd."' AND GRADE=".$grade." AND GENDER_CD='".$gendercd."';";
        $uid = $this->db->getSeqAfterQuery($query);
        if ($uid) {
            session_cache_expire(1000*60*2);
            $_SESSION["SUBJECT_SEQ"] = $uid;
        }
        return $uid;
    }

}

