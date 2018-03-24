<?php
namespace src\mgr;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class MgrQuestionDAO {
    
    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }
    
    public function getList() {
        $query =        "SELECT A.QUESTION_SEQ, MIN(A.ITEM) AS ITEM, B.ORD ";
        $query = $query."  FROM QM_QUESTION_ITEM_TB A ";
        $query = $query." INNER JOIN QM_QUESTION_TB B ";
        $query = $query."         ON B.QUESTION_SEQ = A.QUESTION_SEQ ";
        $query = $query."        AND B.USE_YN='Y' ";
        $query = $query." WHERE A.ORD=1 ";
        $query = $query." GROUP BY QUESTION_SEQ, B.ORD ";
        $query = $query." ORDER BY B.ORD;";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }
    
    public function sortQuestion($questionseq, $ord) {
        $query = "UPDATE QM_QUESTION_TB SET ORD='".$ord."' WHERE QUESTION_SEQ='".$questionseq."';";
        $this->db->query($query);
    }
    
    public function getDetail($questionseq) {
        $query = "SELECT QUESTION_ITEM_SEQ, ITEM, ORD, FIX_YN FROM QM_QUESTION_ITEM_TB WHERE QUESTION_SEQ='".$questionseq."' ORDER BY ORD ASC;";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }

    public function getNewQuestionSeq() {
        $query = "INSERT INTO QM_QUESTION_TB (ORD, USE_YN) SELECT IFNULL(MAX(ORD),0)+1, 'Y' FROM QM_QUESTION_TB;";
        return $this->db->getSeqAfterQuery($query);
    }

    public function addItem($questionseq, $item, $ord, $fixyn) {
        $query = "INSERT INTO QM_QUESTION_ITEM_TB (QUESTION_SEQ, ITEM, ORD, FIX_YN) VALUES ('".$questionseq."', '".$item."', '".$ord."', '".$fixyn."');";
        return $this->db->getSeqAfterQuery($query);
    }
    
    public function dropItems($questionseq) {
        $query = "DELETE FROM QM_QUESTION_ITEM_TB WHERE QUESTION_SEQ='".$questionseq."'";
        return $this->db->getSeqAfterQuery($query);
    }
    
    public function sortItem($questionitemseq, $ord) {
        $query = "UPDATE QM_QUESTION_ITEM_TB SET ORD='".$ord."' WHERE QUESTION_ITEM_SEQ='".$questionitemseq."';";
        $this->db->query($query);
    }
    
    public function dropQuestion($questionseq) {
        $query = "UPDATE QM_QUESTION_TB SET USE_YN='N' WHERE QUESTION_SEQ='".$questionseq."'";
        $this->db->query($query);
    }
    
    
    public function getChartData($regdt, $questionseq, $schoolcd) {
        
        $query =        "SELECT X.SCHOOL_CD, X.ANSWER, IFNULL(SUM(X.A_CNT),0) AS A_CNT, IFNULL(SUM(X.B_CNT),0) AS B_CNT \n";
        $query = $query."  FROM ( \n";
        $query = $query."       SELECT S.SCHOOL_CD, R.ANSWER \n";
        $query = $query."            , CASE WHEN S.GENDER_CD = 'A' THEN IFNULL(COUNT(R.ANSWER), 0) ELSE NULL END AS A_CNT \n";
        $query = $query."            , CASE WHEN S.GENDER_CD='B' THEN IFNULL(COUNT(R.ANSWER), 0) ELSE NULL END AS B_CNT \n";
        $query = $query."         FROM QM_QUESTION_RESULT_TB    R \n";
        $query = $query."        INNER JOIN MM_SUBJECT_TB       S ON R.SUBJECT_SEQ = S.SUBJECT_SEQ \n";
        $query = $query."        INNER JOIN QM_QUESTION_TB      K ON K.QUESTION_SEQ = R.QUESTION_SEQ \n";
        $query = $query."        WHERE K.QUESTION_SEQ = ".$questionseq." \n";

        if (isset($schoolcd) && strlen($schoolcd)==1) {
            $query = $query."           AND S.SCHOOL_CD = '".$schoolcd."' \n";
        }
        
        if (isset($regdt) && strlen($regdt)==10) {
            $query = $query."           AND S.JOIN_DT > '".$regdt."' AND S.JOIN_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
        }
        
        $query = $query."        GROUP BY S.SCHOOL_CD, S.GENDER_CD, R.ANSWER \n";
        $query = $query."       ) X \n";
        $query = $query." GROUP BY X.SCHOOL_CD, X.ANSWER \n";
        $query = $query." ORDER BY X.ANSWER ASC; \n";

        $rows = NULL;
        $arr   = $this->db->query($query);
        if (isset($arr)) {
            while($rs=mysqli_fetch_array($arr)) {
                $rows[] = $rs;
            }
            return $rows;
        }
        return NULL;
    }
    
}

