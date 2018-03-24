<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrResultDAO.php');

$uid = 0;
if (isset($_POST["subjectseq"])) {
    $dao = new \src\mgr\MgrResultDAO();
    $dao->dropQuestionResult(implode(",", $_POST["subjectseq"]));
    $uid = 1;
}
echo $uid;
?>