<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/inc/Session.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/src/qm/QuestionDAO.php');

//htmlspecialchars

$rid = 0;
if (isset($_POST["question"]) && isset($_POST["who"]) && isset($_POST["answer"])) {
    $questioncd = substr($_POST["question"], 0, 1);
    $answer     = substr($_POST["answer"], 0, 30);
    $dao = new src\qm\QuestionDAO();
    $rid = $dao->addResult($questioncd, $answer, $who);
}
echo $rid;
?>