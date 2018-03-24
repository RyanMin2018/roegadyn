<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrQuestionDAO.php');

$rid = 0;
if (isset($_POST["answer"])) {
    $answer = htmlspecialchars($_POST["answer"]);
    $arr    = explode("-", $answer); 
    if (isset($arr) && count($arr)>0) {
        $dao    = new src\mgr\MgrQuestionDAO();
        for($i=0; $i<count($arr); $i++) {
            $dao->sortQuestion($arr[$i], ($i+1));
        }
        $rid = 1;
    }
}
echo $rid;
?>