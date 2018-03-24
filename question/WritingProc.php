<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/inc/Session.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/src/qm/EssayDAO.php');

$rid = 0;
if (isset($_POST["essay"]) && isset($_POST["who"])) {
    $essay = htmlspecialchars($_POST["essay"]);
    $dao   = new src\qm\EssayDAO();
    $rid   = $dao->regist($who, $essay);
}
echo $rid;
?>