<?php
namespace src\qm;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class QuestionDAO {

    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }

    /* get first question. */
    private function getFirstQuestion() {
        $query  = "SELECT IFNULL(MIN(ORD), 0) AS ORD FROM QM_QUESTION_TB WHERE USE_YN='Y'";
        $result = $this->db->query($query);
        $row    = mysqli_fetch_object($result);
        return $row->ORD;
    }
    
    /* get question list. */
    public function getQuestions() {
        $rows = NULL;
        $query = "SELECT ORD FROM QM_QUESTION_TB WHERE USE_YN='Y' ORDER BY ORD ASC";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }
    
    /* get question */
    public function getQuestion($no) {
        if ($no==0) $no = $this->getFirstQuestion();
        if ($no==0) return NULL;
        else {
            $rows = NULL;
            $query = "SELECT ORD, ITEM, FIX_YN, QUESTION_SEQ FROM QM_QUESTION_ITEM_TB WHERE QUESTION_SEQ = (SELECT QUESTION_SEQ FROM QM_QUESTION_TB WHERE ORD='".$no."')";
            $arr   = $this->db->query($query);
            while($rs=mysqli_fetch_array($arr)) {
                $rows[] = $rs;
            }
            return $rows;
        }
    }
    
    /* insert into result table */
    public function addResult($questioncd, $answer, $uid) {
        $query = "DELETE FROM QM_QUESTION_RESULT_TB WHERE SUBJECT_SEQ = '".$uid."' AND QUESTION_SEQ='".$questioncd."';";
        $this->db->query($query);
        $query = "INSERT INTO QM_QUESTION_RESULT_TB (SUBJECT_SEQ, QUESTION_SEQ, ANSWER, REG_DT) VALUES ('".$uid."', '".$questioncd."', '".$answer."', NOW());";
        return $this->db->getSeqAfterQuery($query);
    }

    
}

