<?php
namespace src\mgr;

include_once($_SERVER["DOCUMENT_ROOT"].'/src/db/DbConn.php');

class MgrSurveyDAO {

    public function __construct() {
        $this->db  = new \src\db\DbConn();
    }
    
    public function getList($surveyseq) {
        $query = 'SELECT SURVEY_ITEM_SEQ, SURVEY_SEQ, ITEM, ORD FROM QM_SURVEY_ITEM_TB WHERE SURVEY_SEQ='.$surveyseq.' AND USE_YN="Y" ORDER BY ORD ASC';
        $rows = NULL;
        $arr   = $this->db->query($query);
        if (isset($arr)) {
            while($rs=mysqli_fetch_array($arr)) {
                $rows[] = $rs;
            }
        }
        return $rows;
    }
    
    public function insert($surveyseq, $item) {
        $query = 'INSERT INTO QM_SURVEY_ITEM_TB (SURVEY_SEQ, ITEM, ORD) SELECT "'.$surveyseq.'", "'.$item.'", IFNULL(MAX(ORD),0)+1 FROM QM_SURVEY_ITEM_TB WHERE SURVEY_SEQ='.$surveyseq.' AND USE_YN="Y";';
        return $this->db->getSeqAfterQuery($query);
    }
    
    public function drop($surveyitemseq) {
        $query ='UPDATE QM_SURVEY_ITEM_TB SET USE_YN="N" WHERE SURVEY_ITEM_SEQ='.$surveyitemseq;
        $this->db->query($query);
        
        $query ='UPDATE QM_SURVEY_ITEM_TB SET ORD=ORD-1 WHERE SURVEY_SEQ = (SELECT SURVEY_SEQ FROM QM_SURVEY_ITEM_TB WHERE SURVEY_ITEM_SEQ = '.$surveyitemseq.') AND ORD > (SELECT ORD FROM QM_SURVEY_ITEM_TB WHERE SURVEY_ITEM_SEQ = '.$surveyitemseq.');';
        $this->db->query($query);
    }
 
    public function sort($surveyitemseq, $item, $ord) {
        $query = 'UPDATE QM_SURVEY_ITEM_TB SET ITEM="'.$item.'", ORD="'.$ord.'" WHERE SURVEY_ITEM_SEQ='.$surveyitemseq;
        $this->db->query($query);
    }
    
    
    private function getSurveyOrd() {
        $query = "SELECT ORD, LPAD(ORD, 6, '0') AS LORD FROM QM_SURVEY_ITEM_TB WHERE SURVEY_SEQ=1 AND USE_YN='Y' ORDER BY ORD ASC;";
        $arr   = $this->db->query($query);
        while($rs=mysqli_fetch_array($arr)) {
            $rows[] = $rs;
        }
        return $rows;
    }
 
    
    public function getResult($schoolcd, $gendercd, $regdt, $surveyseq) {
        $arrOrd = $this->getSurveyOrd();
        
        if (isset($arrOrd) && count($arrOrd)>0) {
            $intCnt = count($arrOrd);
            $main = "";
            $sub = "";
            
            foreach ($arrOrd as $o) {
                $main = $main.", IFNULL(MAX(X.Q".$o['LORD']."), '') AS Q".$o['LORD']."\n";
                $sub  = $sub.", IF(Q.ORD=".$o['ORD'].", R.RESULT, NULL) AS Q".$o['LORD']."\n";
            }
            
            $query =        "SELECT ".$intCnt." AS Q_CNT, X.SUBJECT_SEQ, X.SCHOOL_CD, X.GRADE, X.GENDER_CD, LPAD(X.SEQ, 6, '0') AS SEQ \n";
            $query = $query.$main;
            $query = $query."     , MIN(X.JOIN_DT) AS MIN_REG_DT \n";
            $query = $query."  FROM ( \n";
            $query = $query."        SELECT S.SUBJECT_SEQ, S.SCHOOL_CD, S.GRADE, S.GENDER_CD, S.SEQ, Q.ORD, S.JOIN_DT \n";
            $query = $query.$sub;
            $query = $query."          FROM QM_SURVEY_RESULT_TB    R \n";
            $query = $query."         INNER JOIN MM_SUBJECT_TB     S  ON R.SUBJECT_SEQ = S.SUBJECT_SEQ \n";
            $query = $query."         INNER JOIN QM_SURVEY_ITEM_TB Q ON Q.SURVEY_ITEM_SEQ = R.SURVEY_ITEM_SEQ \n";
            $query = $query."         WHERE R.SURVEY_SEQ=".$surveyseq." \n";
            
            if (isset($schoolcd) && strlen($schoolcd)==1) {
                $query = $query."           AND S.SCHOOL_CD = '".$schoolcd."' \n";
            }
            
            if (isset($gendercd) && strlen($gendercd)==1) {
                $query = $query."           AND S.GENDER_CD = '".$gendercd."' \n";
            }
            
            if (isset($regdt) && strlen($regdt)==10) {
                $query = $query."           AND S.JOIN_DT > '".$regdt."' AND S.JOIN_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
            }
            
            
            $query = $query."       ) X \n";
            $query = $query." GROUP BY X.SUBJECT_SEQ, X.SCHOOL_CD, X.GRADE, X.GENDER_CD, X.SEQ \n";
            $query = $query." ORDER BY X.JOIN_DT DESC, X.SEQ DESC, X.ORD ASC; \n";
            
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

    
    public function getChartData($regdt, $surveyseq, $surveyitemseq, $schoolcd) {
        $query =        "SELECT X.ITEM, X.ORD, X.SCHOOL_CD, X.RESULT, IFNULL(SUM(X.A_CNT),0) AS A_CNT, IFNULL(SUM(X.B_CNT),0) AS B_CNT \n";
        $query = $query."  FROM ( \n";
        $query = $query."        SELECT Q.ITEM, Q.ORD, S.SCHOOL_CD, R.RESULT \n";
        $query = $query."             , CASE WHEN S.GENDER_CD = 'A' THEN IFNULL(COUNT(R.RESULT), 0) ELSE NULL END AS A_CNT \n";
        $query = $query."             , CASE WHEN S.GENDER_CD='B' THEN IFNULL(COUNT(R.RESULT), 0) ELSE NULL END AS B_CNT \n";
        $query = $query."          FROM QM_SURVEY_RESULT_TB    R \n";
        $query = $query."         INNER JOIN MM_SUBJECT_TB     S  ON R.SUBJECT_SEQ = S.SUBJECT_SEQ \n";
        $query = $query."         INNER JOIN QM_SURVEY_ITEM_TB Q ON Q.SURVEY_ITEM_SEQ = R.SURVEY_ITEM_SEQ AND Q.SURVEY_ITEM_SEQ = ".$surveyitemseq." \n";
        $query = $query."         WHERE R.SURVEY_SEQ = ".$surveyseq." \n";

        if (isset($schoolcd) && strlen($schoolcd)==1) {
            $query = $query."           AND S.SCHOOL_CD = '".$schoolcd."' \n";
        }
        
        if (isset($regdt) && strlen($regdt)==10) {
            $query = $query."           AND S.JOIN_DT > '".$regdt."' AND S.JOIN_DT < DATE_ADD('".$regdt."', INTERVAL 1 DAY) \n";
        }

        $query = $query."         GROUP BY Q.ITEM, Q.ORD, S.SCHOOL_CD, S.GENDER_CD, R.RESULT \n";
        $query = $query."       ) X \n";
        $query = $query." GROUP BY X.ITEM, X.ORD, X.SCHOOL_CD, X.RESULT \n";
        $query = $query." ORDER BY X.ORD ASC, X.RESULT ASC; \n";
            
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



