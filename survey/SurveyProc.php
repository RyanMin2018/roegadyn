<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/inc/Session.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/src/qm/SurveyDAO.php');

//htmlspecialchars

$rid = 0;
if (isset($_POST["surveyseq"]) && isset($_POST["who"]) && isset($_POST['cnt'])) {
    $no = substr($_POST["surveyseq"], 0, 1);
    $dao = new src\qm\SurveyDAO();

    $j = 0;
    $text = "";
    for ($i=0; $i<$_POST['cnt']; $i++) {
        $j++;
        $val = $_POST["seq_".$j];    
        // $dao->addResult($no, $j, $val, $who);
        $text = $text.",('".$who."', '".$no."', '".$j."', '".$val."')";
    }
    $text = substr($text, 1);
    $dao->addResults($who, $no, $text);
    $rid = 1;
}
echo $rid;

?>