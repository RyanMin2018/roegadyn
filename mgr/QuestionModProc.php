<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/MgrQuestionDAO.php');

// $log = '/home/dolgamza/rst/log/rst.log';

$rid = 0;
if (isset($_POST["questionseq"]) && isset($_POST["answer"]) && isset($_POST["cnt"])) {
    $arr    = explode("-", $_POST["answer"]);
    if (isset($arr) && count($arr)>0) {
        $questionseq = $_POST["questionseq"];
        $dao = new src\mgr\MgrQuestionDAO();
        $dao->dropItems($questionseq);
        
        $ord = 0;
        for($i=0; $i<count($arr); $i++) {
            $seq = $arr[$i];
            if (isset($_POST['item_'.$seq])) {
                $ord++;
                $item  = $_POST['item_'.$seq];
                $fixyn = (isset($_POST['fixyn_'.$seq])) ? 'Y' : 'N';
                $dao->addItem($questionseq, $item, $ord, $fixyn);
            }
        }
        $rid = 1;
    }
}
echo $rid;
?>