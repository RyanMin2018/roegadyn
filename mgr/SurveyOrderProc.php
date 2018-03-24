<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrSurveyDAO.php');
$log = '/home/dolgamza/rst/log/rst.log';
$rid = 0;
if (isset($_POST['surveyseq']) && isset($_POST["answer"])) {
    $surveyseq = $_POST['surveyseq'];
    $answer    = htmlspecialchars($_POST["answer"]);
    $arr       = explode("-", $answer);
    if (isset($arr) && count($arr)>0) {
        $dao    = new src\mgr\MgrSurveyDAO();
        for($i=0; $i<count($arr); $i++) {
            file_put_contents($log, 'item_'.$arr[$i]);
            if (isset($_POST['item_'.$arr[$i]])) {
                file_put_contents($log, $_POST['item_'.$arr[$i]]);
                $text = trim(htmlspecialchars($_POST['item_'.$arr[$i]]));
                $dao->sort($arr[$i], $text, ($i+1));
            }
        }
        $rid = 1;
    }
}
echo $rid;
?>