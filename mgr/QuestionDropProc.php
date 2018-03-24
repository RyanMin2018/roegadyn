<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrQuestionDAO.php');

$rid = 0;
if (isset($_POST["questionseq"])) {
    $questionseq = $_POST["questionseq"];
    $dao = new src\mgr\MgrQuestionDAO();
    $dao->dropQuestion($questionseq);
    $rid = 1;
}
echo $rid;
?>