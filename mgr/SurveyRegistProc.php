<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrSurveyDAO.php');

$uid = 0;
if (isset($_POST["surveyseq"]) && isset($_POST["item"])) {
    $surveyseq  = substr($_POST["surveyseq"], 0, 11);
    $item       = substr(trim(htmlspecialchars($_POST["item"])), 0, 50);
    $dao = new \src\mgr\MgrSurveyDAO();
    $uid = $dao->insert($surveyseq, $item);
}
echo $uid;
?>