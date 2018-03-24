<?php
namespace src\qm;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class SurveyDAO {

    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }
    
    /* get first question. */
    private function getRecentSurvey() {
        $query  = "SELECT IFNULL(MAX(SURVEY_SEQ), 0) AS SURVEY_SEQ FROM QM_SURVEY_TB WHERE USE_YN='Y'";
        $result = $this->db->query($query);
        $row    = mysqli_fetch_object($result);
        return $row->SURVEY_SEQ;
    }
    
    /* get question list. */
    public function getSurveys() {
        $rows = NULL;
        $query = "SELECT ORD FROM QM_SURVEY_TB WHERE USE_YN='Y' ORDER BY ORD ASC";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }
    
    /* get question */
    public function getSurvey($no) {
        if ($no==0) $no = $this->getRecentSurvey();
        if ($no==0) return NULL;
        else {
            $rows = NULL;
            $query = "SELECT SURVEY_SEQ, SURVEY_ITEM_SEQ, ITEM FROM QM_SURVEY_ITEM_TB WHERE SURVEY_SEQ = '".$no."' AND USE_YN='Y' ORDER BY ORD ASC;";
            $arr   = $this->db->query($query);
            while($rs=mysqli_fetch_array($arr)) {
                $rows[] = $rs;
            }
            return $rows;
        }
    }
    
    /* insert into result table */
    public function addResult($no, $itemno, $val, $uid) {
        $query = "DELETE FROM QM_SURVEY_RESULT_TB WHERE SUBJECT_SEQ = '".$uid."' AND SURVEY_SEQ='".$no."' AND SURVEY_ITEM_SEQ='".$itemno."';";
        $this->db->query($query);
        $query = "INSERT INTO QM_SURVEY_RESULT_TB (SUBJECT_SEQ, SURVEY_SEQ, SURVEY_ITEM_SEQ, RESULT) VALUES ('".$uid."', '".$no."', '".$itemno."', '".$val."');";
        return $this->db->getSeqAfterQuery($query);
    }

    public function addResults($uid, $no, $text) {
        $query = "DELETE FROM QM_SURVEY_RESULT_TB WHERE SUBJECT_SEQ = '".$uid."' AND SURVEY_SEQ='".$no."';";
        $this->db->query($query);
        $query = "INSERT INTO QM_SURVEY_RESULT_TB (SUBJECT_SEQ, SURVEY_SEQ, SURVEY_ITEM_SEQ, RESULT) VALUES ".$text;
        return $this->db->getSeqAfterQuery($query);
    }

}

