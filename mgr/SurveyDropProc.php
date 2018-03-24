<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrSurveyDAO.php');

$uid = 0;
if (isset($_GET["surveyitemseq"])) {
    $surveyitemseq  = substr($_GET["surveyitemseq"], 0, 11);
    $dao = new \src\mgr\MgrSurveyDAO();
    $dao->drop($surveyitemseq);
    $uid = $surveyitemseq;
}
echo $uid;
?>