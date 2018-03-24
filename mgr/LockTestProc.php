<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/SessionStatus.php');
$lo  = new src\mm\SessionStatus();
$who = $lo->getManagerUserId();

include_once($_SERVER["DOCUMENT_ROOT"].'/src/mgr/LockTestDAO.php');

$dao = new \src\mgr\LockTestDAO();

$uid = "";
if (isset($_POST["authkey"]) && isset($_POST["hour"])) {
    $dao->updateAuthKey($_POST["authkey"], $_POST["hour"]);
    $uid = $_POST["authkey"];
}
echo $uid;
?>