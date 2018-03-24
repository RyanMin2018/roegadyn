<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/src/mm/ManagerDAO.php');

$r = false;
if (isset($_POST["logid"]) && isset($_POST['logpw'])) {
    $dao = new src\mm\ManagerDAO();
    $r = $dao->logon(substr($_POST["logid"], 0, 15), substr($_POST['logpw'], 0, 15));

}
echo $r;
?>