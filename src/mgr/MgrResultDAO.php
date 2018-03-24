<?php
namespace src\mgr;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class MgrResultDAO {

    private $db;
    
    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }

    private function getQuestionOrd() {
        $query = "SELECT ORD, LPAD(ORD, 6, '0') AS LORD FROM QM_QUESTION_TB WHERE USE_YN = 'Y' ORDER BY ORD ASC;";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }
    
    public function getResult($schoolcd, $gendercd, $regdt) {
        $arrOrd = $this->getQuestionOrd();
        
        if (isset($arrOrd) && count($arrOrd)>0) {
            $intCnt = count($arrOrd);
            $main = "";
            $sub = "";
            
            foreach ($arrOrd as $o) {
                $main = $main.", IFNULL(MAX(X.Q".$o['LORD']."), '') AS Q".$o['LORD']."\n";
                $sub  = $sub.", IF(Q.ORD=".$o['ORD'].", R.ANSWER, NULL) AS Q".$o['LORD']."\n";
            }
            
            $query =        "SELECT ".$intCnt." AS Q_CNT, X.SUBJECT_SEQ, X.SCHOOL_CD, X.GRADE, X.GENDER_CD, LPAD(X.SEQ, 6, '0') AS SEQ \n";
            $query = $query.$main;
            $query = $query."     , COUNT(X.QUESTION_SEQ) AS CNT";
            $query = $query."     , TIMESTAMPDIFF(MINUTE, MIN(X.REG_DT), MAX(X.REG_DT)) AS DURATION \n";
            $query = $query."     , X.JOIN_DT     AS MIN_REG_DT \n";
            $query = $query."     , MAX(X.REG_DT) AS MAX_REG_DT \n";
            $query = $query."  FROM ( \n";
            $query = $query."        SELECT S.SUBJECT_SEQ, S.SCHOOL_CD, S.GRADE, S.GENDER_CD, S.SEQ, R.QUESTION_SEQ, Q.ORD, R.REG_DT, S.JOIN_DT \n";
            $query = $query.$sub;
            $query = $query."          FROM QM_QUESTION_RESULT_TB R \n";
            $query = $query."         INNER JOIN MM_SUBJECT_TB    S \n";
            $query = $query."                 ON R.SUBJECT_SEQ = S.SUBJECT_SEQ \n";
            $query = $query."         INNER JOIN QM_QUESTION_TB   Q \n";
            $query = $query."                 ON R.QUESTION_SEQ = Q.QUESTION_SEQ \n";
            $query = $query."         WHERE 1=1 \n";

            if (isset($schoolcd) && strlen($schoolcd)==1) {
                $query = $query."           AND S.SCHOOL_CD = '".$schoolcd."' \n";
            }

            if (isset($gendercd) && strlen($gendercd)==1) {
                $query = $query."           AND S.GENDER_CD = '".$gendercd."' \n";
            }
            
            if (isset($regdt) && strlen($regdt)==10) {
                $query = $query."           AND R.REG_DT > '".$regdt."' AND R.REG_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
            }
            
            
            $query = $query."       ) X \n";
            $query = $query." GROUP BY X.SUBJECT_SEQ, X.SCHOOL_CD, X.GRADE, X.GENDER_CD, X.SEQ, X.JOIN_DT \n";
            $query = $query." ORDER BY X.JOIN_DT DESC, X.ORD ASC; \n";
    
            $rows = NULL;
            $arr   = $this->db->query($query);
            if (isset($arr)) {
                while($rs=mysqli_fetch_array($arr)) {
                    $rows[] = $rs;
                }
                return $rows;
            }
        } 
        return NULL;
    }
    
    
    public function getEssayList($schoolcd, $gendercd, $regdt, $page) {
        $intBlockSize = 20;
        $intMin = $page*$intBlockSize - $intBlockSize;
        $intMax = $page*$intBlockSize - 1;

        $countQuery   = "SELECT COUNT(*) AS CNT \n";
       
        $query = "";
        $query = $query."  FROM QM_ESSAY_TB E \n";
        $query = $query." INNER JOIN MM_SUBJECT_TB S \n";
        $query = $query."    ON S.SUBJECT_SEQ = E.SUBJECT_SEQ \n";
        $query = $query." WHERE 1=1 \n";
        
        if (isset($schoolcd) && strlen($schoolcd)==1) {
            $query = $query."   AND S.SCHOOL_CD = '".$schoolcd."' \n";
        }
        
        if (isset($gendercd) && strlen($gendercd)==1) {
            $query = $query."   AND S.GENDER_CD = '".$gendercd."' \n";
        }
        
        if (isset($regdt) && strlen($regdt)==10) {
            $query = $query."   AND E.REG_DT > '".$regdt."' AND E.REG_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
        }
        
        $cnts = $this->db->query($countQuery.$query);
        $row    = mysqli_fetch_object($cnts);
        $intCnt = $row->CNT;
        
        $mainQuery  = "SELECT ".$intCnt." AS CNT, S.SUBJECT_SEQ, S.SCHOOL_CD, S.GRADE, S.GENDER_CD, LPAD(S.SEQ, 6, '0') AS LSEQ, E.ESSAY_SEQ, E.REG_DT, E.CONTENT \n";
        
        $tail = " ORDER BY S.SUBJECT_SEQ DESC \n";
        $tail = $tail." LIMIT ".$intMin.", ".$intBlockSize.";";
        
        $rows = NULL;
        $arr   = $this->db->query($mainQuery.$query.$tail);
        if (isset($arr)) {
            while($rs=mysqli_fetch_array($arr)) {
                $rows[] = $rs;
            }
            return $rows;
        }
        return NULL;
        
    }

    
    public function getEssayWordCloud($schoolcd, $gendercd, $regdt) {
       
        $query =        "SELECT E.CONTENT \n";
        $query = $query."  FROM QM_ESSAY_TB E \n";
        $query = $query." INNER JOIN MM_SUBJECT_TB S \n";
        $query = $query."    ON S.SUBJECT_SEQ = E.SUBJECT_SEQ \n";
        $query = $query." WHERE 1=1 \n";
        
        if (isset($schoolcd) && strlen($schoolcd)==1) {
            $query = $query."   AND S.SCHOOL_CD = '".$schoolcd."' \n";
        }
        
        if (isset($gendercd) && strlen($gendercd)==1) {
            $query = $query."   AND S.GENDER_CD = '".$gendercd."' \n";
        }
        
        if (isset($regdt) && strlen($regdt)==10) {
            $query = $query."   AND E.REG_DT > '".$regdt."' AND E.REG_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
        }
 
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
    
    
    public function dropQuestionResult($subjectseqs) {
        $query = "DELETE FROM QM_QUESTION_RESULT_TB WHERE SUBJECT_SEQ IN (".$subjectseqs.");";
        $this->db->query($query);
    }
    
    public function dropEssayResult($subjectseqs) {
        $query = "DELETE FROM QM_ESSAY_TB WHERE SUBJECT_SEQ IN (".$subjectseqs.");";
        $this->db->query($query);
    }
    
    public function dropSurveyResult($subjectseqs) {
        $query = "DELETE FROM QM_SURVEY_RESULT_TB WHERE SUBJECT_SEQ IN (".$subjectseqs.");";
        $this->db->query($query);
    }
    
}

